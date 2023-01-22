<template>
    <div>
        <h1>Users</h1>
        <v-data-table :items="items" :headers="headers">
            <template v-slot:item.is_active="{ item }">
                <BooleanDisplay :value="item.is_active"></BooleanDisplay>
            </template>
            <template v-slot:item.is_admin="{ item }">
                <BooleanDisplay :value="item.is_admin"></BooleanDisplay>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";

export default {
    name: "UsersPage",
    extends: ResourceListPage,
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
                    value: "email",
                    text: "Email",
                },
                {
                    value: "company.name",
                    text: "Company",
                },
                {
                    value: "is_active",
                    text: "Active",
                },
                {
                    value: "is_admin",
                    text: "Admin",
                },
            ],
            resourceTerm: "users",
        };
    },
};
</script>

<style></style>
