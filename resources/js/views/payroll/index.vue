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
            :items="items"
            :headers="headers"
            :search="search"
        >
            <template v-slot:item.date="{ item }">
                {{ item.date_from }} to {{ item.date_until }}
            </template>
            <template v-slot:item.actions="{ item }">
                <v-btn
                    small
                    elevation="0"
                    color="amber darken-2"
                    @click="openPrintPreview(item)"
                    dark
                >
                    <v-icon class="mr-2">mdi-printer</v-icon> Print Preview
                </v-btn>
            </template>

            <template v-slot:item.total="{ item }">
                {{ currency(item.total) }}
            </template>
        </v-data-table>

        <CustomModal
            v-slot:default="{ close }"
            ref="printPreviewModal"
            v-bind="printPayrollAttrs"
        >
            <PrintPreview :item="onPreview" @close="close"></PrintPreview>
        </CustomModal>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";
import CreateForm from "./CreateForm";
import UpdateForm from "~/views/management/employees/UpdateForm";
import PrintPreview from "~/components/app/payroll/PrintPreview";
import { currency } from "~/helpers";

export default {
    name: "EmployeesManagement",
    extends: ResourceListPage,
    components: {
        CreateForm,
        UpdateForm,
        PrintPreview,
    },
    data() {
        return {
            rawHeaders: [
                {
                    value: "employee_code",
                    text: "Employee Code",
                },
                {
                    value: "date_from",
                    text: "Date From",
                    sort: (a, b) => new Date(a) - new Date(b),
                },
                {
                    value: "date_until",
                    text: "Date To",
                    sort: (a, b) => new Date(a) - new Date(b),
                },
                {
                    value: "employee.full_name",
                    text: "Name",
                },
                {
                    value: "total",
                    text: "Total Salary",
                },
                {
                    value: "actions",
                    text: "Actions",
                },
            ],
            resourceTerm: "payroll",
            createModalAttrs: {
                title: "Create Payroll",
                btnLabel: "Create New",
                width: 750,
            },
            printPayrollAttrs: {
                title: "",
                btnLabel: "Print Preview",
                width: 800,
                btnAttrs: {
                    color: "amber darken-2",
                    hide: true,
                    actualIcon: "mdi-printer",
                },
            },
            additionalHeaders: [],
            resourceName: "Payroll",
            onPreview: {},
            reportValues: {
                "employee.full_name": (item) => {
                    return item.employee.full_name;
                },
            },
        };
    },
    methods: {
        currency,
        openPrintPreview(item) {
            this.onPreview = item;
            this.$refs.printPreviewModal.open();
        },
    },
};
</script>
