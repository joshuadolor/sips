<template>
    <div>
        <h1>Companies</h1>
        <v-data-table :items="items" :headers="headers">
            <template v-slot:item.is_active="{ item }">
                <BooleanDisplay :value="item.is_active"></BooleanDisplay>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";

export default {
    name: "CompaniesPage",
    extends: ResourceListPage,
    data() {
        return {
            rawHeaders: [
                {
                    value: "name",
                    text: "Name",
                },
                {
                    value: "is_active",
                    text: "Active",
                },
            ],
            resourceTerm: "companies",
        };
    },
};
</script>

<style></style>
