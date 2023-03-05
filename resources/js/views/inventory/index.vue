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
        >
            <template v-slot:item.created_at="{ item }">
                {{ format(new Date(item.created_at), "Y-MM-dd HH:mm:ss") }}
            </template>

            <template v-slot:item.type="{ item }">
                <v-chip color="primary" v-if="item.type === 'receive'"
                    >Receive</v-chip
                >
                <v-chip v-else-if="item.type === 'transfer'" dark color="black"
                    >Transfer</v-chip
                >
                <v-chip v-else-if="item.type === 'sales'" dark color="green"
                    >Sales</v-chip
                >
            </template>
        </v-data-table>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";
import CreateForm from "./CreateForm";
import UpdateForm from "./UpdateForm";
import { currency } from "~/helpers";
import Service from "~/services/ProductMovementService";
import { format } from "date-fns";

export default {
    name: "Inventory",
    extends: ResourceListPage,
    components: {
        CreateForm,
        UpdateForm,
    },
    methods: {
        currency,
        format,
        async fetchData() {
            this.$root.$emit("pageLoading");
            try {
                this.items = await this.service.get(this.getParams);
            } catch (exception) {
                const message = exception.getMessage() || "Invalid Credentials";
                this.$root.$emit("showSnackbar", message, "red");
            }
            this.$root.$emit("pageLoading", false);
        },
    },
    data() {
        return {
            rawHeaders: [
                {
                    value: "created_at",
                    text: "Created At",
                },
                {
                    value: "product.item_code",
                    text: "Item Code",
                },
                {
                    value: "product.name",
                    text: "Name",
                },
                {
                    value: "type",
                    text: "Type",
                },
                {
                    value: "quantity",
                    text: "Processed Quantity",
                },
                {
                    value: "agent.full_name",
                    text: "Agent",
                },
            ],
            resourceTerm: "products",
            createModalAttrs: {
                title: "Receive/Transfer Product",
                btnLabel: "Receive/Transfer",
            },

            resourceName: "Inventory",
            service: Service,
            reportValues: {
                created_at: (item) =>
                    format(new Date(item.created_at), "Y-MM-dd HH:mm:ss"),
                "product.item_code": (item) => item.product.item_code,
                "product.name": (item) => item.product.name,
                "agent.full_name": (item) => item.agent.full_name,
            },
        };
    },
};
</script>
