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
                    <v-text-field
                        prepend-inner-icon="mdi-magnify"
                        v-model="search"
                        placeholder="Search"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-container>

        <v-data-table :search="search" :items="items" :headers="rawHeaders">
            <template v-slot:item.created_at="{ item }">
                {{ format(new Date(item.created_at), "Y-MM-dd HH:mm:ss") }}
            </template>
        </v-data-table>
    </div>
</template>

<script>
import InventoryModule from "~/views/inventory";
import CreateForm from "./CreateForm";

export default {
    name: "Sales",
    components: {
        CreateForm,
    },
    extends: InventoryModule,
    data() {
        return {
            resourceName: "Sales",
            getParams: { sales: 1 },
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
                    value: "quantity",
                    text: "Processed Quantity",
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
        };
    },
};
</script>
