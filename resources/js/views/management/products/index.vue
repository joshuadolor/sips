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
                        Save Report</v-btn
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
            :headers="headers"
        >
            <template v-slot:item.price="{ item }">
                {{ currency(item.price) }}
            </template>

            <template v-slot:item.actions="{ item }">
                <v-btn
                    small
                    elevation="0"
                    color="amber darken-2"
                    @click="openUpdateModal(item)"
                    dark
                    >Update</v-btn
                >
            </template>
        </v-data-table>
        <CustomModal
            ref="updateModal"
            v-slot:default="{ close }"
            v-bind="updateModalAttrs"
        >
            <UpdateForm
                @success="
                    close();
                    fetchData();
                "
                :currentData="toUpdate"
                @close="close"
            />
        </CustomModal>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";
import CreateForm from "./CreateForm";
import UpdateForm from "./UpdateForm";
import { currency } from "~/helpers";

export default {
    name: "EmployeesManagement",
    extends: ResourceListPage,
    components: {
        CreateForm,
        UpdateForm,
    },
    methods: {
        currency,
    },
    data() {
        return {
            rawHeaders: [
                {
                    value: "item_code",
                    text: "Item Code",
                },
                {
                    value: "name",
                    text: "Name",
                },
                {
                    value: "quantity",
                    text: "In Stock",
                },
                {
                    value: "price",
                    text: "Price",
                },
            ],
            resourceTerm: "products",
            createModalAttrs: {
                title: "Create Product",
                btnLabel: "Create New",
            },
            updateModalAttrs: {
                title: "Update Product",
                btnLabel: "Update",
                btnAttrs: {
                    color: "amber darken-2",
                    hide: true,
                },
            },
            resourceName: "Products",
        };
    },
};
</script>
