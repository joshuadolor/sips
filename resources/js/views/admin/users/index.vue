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
                <v-btn
                    @click="setToUpdate(item.id, 'resetPasswordModal')"
                    color="warning"
                    small
                    class="ma-1"
                    elevation="0"
                    >Reset Password</v-btn
                >
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

        <CustomModal ref="resetPasswordModal" v-bind="resetPasswordAttrs">
            <template>
                Are you sure you want to proceed? This action cannot be undone.
                Default Password is
                <br />
                <br />
                <code>{{ defaultPassword }}</code>
            </template>
            <template v-slot:actions="{ close }">
                <v-spacer />
                <v-btn text @click="close">cancel</v-btn>
                <v-btn
                    color="primary"
                    :loading="resettingPassword"
                    @click="resetPassword(toUpdate)"
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
import resetPassword from "./reset-password";

export default {
    name: "UsersPage",
    extends: ResourceListPage,
    mixins: [toggleUser, resetPassword],
    data() {
        return {
            defaultPassword: "S1ps!",
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
            resetPasswordAttrs: {
                title: "Reset Password",
                btnLabel: "",
                btnAttrs: {
                    color: "amber darken-2",
                    hide: true,
                },
            },
        };
    },
    methods: {
        setToUpdate(val, ref = "toggleModal") {
            this.toUpdate = val;
            this.$refs[ref].open();
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
        this.$on("reset-password-success", () => {
            this.$refs.resetPasswordModal.close();
        });
    },
};
</script>

<style scoped>
code {
    font-size: 3em;
}
</style>
