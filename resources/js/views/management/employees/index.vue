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

        <v-data-table :search="search" :items="items" :headers="headers">
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
import CreateForm from "~/views/management/employees/CreateForm";
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
            ],
            resourceTerm: "employees",
            createModalAttrs: {
                title: "Create Employee",
                btnLabel: "Create New",
            },
            updateModalAttrs: {
                title: "Update Employee",
                btnLabel: "Update",
                btnAttrs: {
                    color: "amber darken-2",
                    hide: true,
                },
            },
            resourceName: "Employees",
        };
    },
};
</script>
