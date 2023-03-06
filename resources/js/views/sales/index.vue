<template>
    <div>
        <v-container>
            <v-row>
                <v-col>
                    <h1 class="d-inline">{{ resourceName }}</h1>
                </v-col>
                <v-col align-self="center" class="text-right">
                    <CustomModal
                        v-slot:default="{ close }"
                        v-bind="createModalAttrs"
                    >
                        <CreateForm
                            @success="
                                close();
                                fetchData();
                            "
                            @close="close"
                        />
                    </CustomModal>
                    <v-btn
                        :disabled="items.length < 1"
                        @click="saveReport"
                        color="warning darken-2"
                    >
                        <v-icon class="mr-2">mdi-download</v-icon>
                        Download Report</v-btn
                    >
                    <v-text-field
                        prepend-inner-icon="mdi-magnify"
                        v-model="search"
                        placeholder="Search"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-container>

        <v-data-table
            @current-items="getFiltered"
            :search="search"
            :items="items"
            :headers="rawHeaders"
            :custom-filter="customSearch"
        >
            <template v-slot:item.code="{ item }">
                {{ "S" + item.id.toString().padStart(6, "0") }}
            </template>
            <template v-slot:item.created_at="{ item }">
                {{ format(new Date(item.created_at), "Y-MM-dd HH:mm:ss") }}
            </template>
            <template v-slot:item.total_cost="{ item }">
                {{ currency(item.total_cost) }}
            </template>
            <template v-slot:item.product_movements="{ item }">
                <v-simple-table
                    class="my-3 grey lighten-2"
                    fixed-header
                    v-if="item.product_movements.length > 0"
                >
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="grey lighten-3 text-left">Code</th>
                                <th class="grey lighten-3 text-left">Name</th>
                                <th class="grey lighten-3 text-center">
                                    Quantity
                                </th>
                                <th class="grey lighten-3 text-right">
                                    Item Price
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="pm in item.product_movements"
                                :key="pm.id"
                            >
                                <td>{{ pm.product.item_code }}</td>
                                <td>{{ pm.product.name }}</td>
                                <td class="text-center">
                                    {{ pm.quantity }}
                                </td>

                                <td class="text-right">
                                    {{ currency(pm.cost) }}
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import InventoryModule from "~/views/inventory";
import CreateForm from "./CreateForm";
import Service from "~/services/SalesService";
import CSVCreator from "~/utils/CSVCreator";
import { format } from "date-fns";

export default {
    name: "Sales",
    components: {
        CreateForm,
    },
    extends: InventoryModule,
    data() {
        return {
            resourceName: "Sales",
            rawHeaders: [
                {
                    value: "code",
                    text: "#",
                },
                {
                    value: "transaction_date",
                    text: "Transaction Date",
                    sort: (a, b) => new Date(a) - new Date(b),
                },
                {
                    value: "product_movements",
                    text: "Items",
                },
                {
                    value: "total_cost",
                    text: "Total Cost",
                    sort: (a, b) => a - b,
                },

                {
                    value: "agent.full_name",
                    text: "Agent",
                },
            ],
            createModalAttrs: {
                title: "Create",
                btnLabel: "Create New",
            },
            service: Service,
            reportValues: {
                code: (item) => "S" + item.id.toString().padStart(6, "0"),
            },
        };
    },
    methods: {
        customSearch(value, search, item) {
            search = search.toLowerCase();
            const { product_movements: pm, agent, id } = item;
            const names = pm.map((p) => p.product.name.toLowerCase());
            const codes = pm.map((p) => p.product.item_code);

            return (
                id.toString().indexOf(search) > -1 ||
                agent.full_name.toLowerCase().indexOf(search) > -1 ||
                names.some((name) => name.indexOf(search) > -1) ||
                codes.some((code) => code.indexOf(search) > -1)
            );
        },
        saveReport() {
            const headers = [
                "id",
                "created_at",
                "item_code",
                "item_name",
                "quantity",
                "item_price",
                "total_item_price",
                "total_cost",
                "agent",
            ];
            const displayHeaders = [
                "Sales ID",
                "Created At",
                "Item Code",
                "Item Name",
                "Quantity",
                "Item Price",
                "Total Item Price",
                "Total Cost",
                "Agent",
            ];
            const rawData = this.filteredItems.map((item) => {
                return this.rawHeaders
                    .map((header) => header.value)
                    .reduce((a, b) => {
                        const value = this.reportValues[b]
                            ? this.reportValues[b](item)
                            : item[b];

                        return { ...a, [b]: value };
                    }, {});
            });
            const data = [];
            const modelValue = (d, pm) => {
                return {
                    id: d.code,
                    created_at: d.created_at,
                    item_code: pm.product.item_code,
                    item_name: pm.product.name,
                    quantity: pm.quantity,
                    item_price: parseFloat(pm.cost) / parseInt(pm.quantity),
                    total_item_price: pm.cost,
                    total_cost: d.total_cost,
                    agent: d["agent.full_name"],
                };
            };

            rawData.forEach((d) => {
                d.product_movements.forEach((pm, idx) => {
                    const value = modelValue(d, pm);

                    if (idx === 0) {
                        data.push(value);
                    } else {
                        const except = [
                            "item_code",
                            "item_name",
                            "quantity",
                            "item_price",
                            "total_item_price",
                        ];
                        const blank = headers
                            .filter((h) => except.indexOf(h) === -1)
                            .reduce((a, b) => {
                                return { ...a, [b]: "" };
                            }, {});
                        data.push({
                            ...value,
                            ...blank,
                        });
                    }
                });
            });

            const csv = new CSVCreator({
                headers,
                data,
                displayHeaders,
            });

            const date = format(new Date(), "Y_MM_dd_HH_mm_ss");

            const fileName = this.resourceName + "_" + date;
            csv.export(fileName);
        },
    },
};
</script>
