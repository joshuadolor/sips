<template>
    <div>
        <h1>App Users</h1>
        <v-data-table :items="items" :headers="headers">
            <template v-slot:item.is_active="{ item }">
                <BooleanDisplay :value="item.is_active"></BooleanDisplay>
            </template>
            <template v-slot:item.is_admin="{ item }">
                <BooleanDisplay :value="item.role > 0"></BooleanDisplay>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-btn
                    small
                    elevation="0"
                    :color="item.is_active ? 'red' : 'primary'"
                    @click="setToUpdate(item)"
                    dark
                >
                    {{ item.is_active ? "Deactivate" : "Activate" }}
                </v-btn>
            </template>
        </v-data-table>
        <CustomModal ref="toggleModal" v-bind="toggleUserAttrs">
            <template>
                Are you sure you want to proceed? This action cannot be undone.
            </template>
            <template v-slot:actions="{ close }">
                <v-spacer />
                <v-btn text @click="close">cancel</v-btn>
                <v-btn
                    color="primary"
                    :loading="togglingUser"
                    @click="toggleUser"
                    elevation="0"
                    >Proceed</v-btn
                >
            </template>
        </CustomModal>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";
import toggleUser from "./toggle-user";

export default {
    name: "UsersPage",
    extends: ResourceListPage,
    mixins: [toggleUser],
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
            toUpdate: {},
        };
    },
    methods: {
        setToUpdate(val) {
            this.toUpdate = val;
            this.$refs.toggleModal.open();
        },
    },
    computed: {
        toggleUserAttrs() {
            const { is_active } = this.toUpdate;
            const actionWord = is_active ? "Deactivate" : "Activate";
            return {
                title: `${actionWord} User`,
                btnLabel: actionWord,
                btnAttrs: {
                    color: "amber darken-2",
                    hide: true,
                },
            };
        },
    },
    mounted() {
        this.$on("toggle-user-success", () => {
            this.$refs.toggleModal.close();
            this.fetchData();
        });
    },
};
</script>

<style></style>
