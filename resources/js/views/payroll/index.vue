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

        <v-data-table :items="items" :headers="headers">
            <template v-slot:item.date="{ item }">
                {{ item.date_from }} - {{ item.date_until }}
            </template>
        </v-data-table>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";
import CreateForm from "./CreateForm";
import UpdateForm from "~/views/management/employees/UpdateForm";

export default {
    name: "EmployeesManagement",
    extends: ResourceListPage,
    components: {
        CreateForm,
        UpdateForm,
    },
    data() {
        return {
            rawHeaders: [
                {
                    value: "employee_code",
                    text: "Employee Code",
                },
                {
                    value: "date",
                    text: "Payroll Date",
                },
                {
                    value: "employee.full_name",
                    text: "Name",
                },
                {
                    value: "total",
                    text: "Total",
                },
            ],
            resourceTerm: "payroll",
            createModalAttrs: {
                title: "Create Payroll",
                btnLabel: "Create New",
                width: 750,
            },
            updateModalAttrs: {
                title: "Update Employee",
                btnLabel: "Update",
                btnAttrs: {
                    color: "amber darken-2",
                    hide: true,
                },
            },
            additionalHeaders: [],
            resourceName: "Payroll",
        };
    },
};
</script>
