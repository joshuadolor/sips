<template>
    <div>
        <h1>Employees</h1>
        <CustomModal>
            <CreateForm />
        </CustomModal>
        <v-data-table :items="items" :headers="headers"> </v-data-table>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";
import CreateForm from "~/views/management/employees/CreateForm";

export default {
    name: "EmployeesManagement",
    extends: ResourceListPage,
    components: {
        CreateForm,
    },
    data() {
        return {
            rawHeaders: [
                {
                    value: "first_name",
                    text: "First Name",
                },
                {
                    value: "last_name",
                    text: "Last Name",
                },
                {
                    value: "middle_name",
                    text: "Middle Name",
                },

                {
                    value: "company.name",
                    text: "Company",
                },
            ],
            resourceTerm: "employees",
        };
    },
};
</script>
