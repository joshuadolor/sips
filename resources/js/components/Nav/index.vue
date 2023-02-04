<template>
    <div v-if="hasUserInfo">
        <v-navigation-drawer v-model="drawer" app>
            <v-sheet color="grey lighten-4" class="pa-4">
                <Logo />
                <v-avatar class="mb-4 d-none" color="grey darken-1" size="64">
                    <v-icon>mdi-account-circle</v-icon>
                </v-avatar>
                <div v-show="hasUserInfo">
                    <div class="title">{{ name }}</div>
                    <div>{{ userInfo.email }}</div>
                </div>
                <CompanySelect
                    v-if="isSuperAdmin"
                    label="Creating/Updating as"
                    :append-empty="true"
                    class="mt-10"
                    @input="(v) => (superAdminCompanyId = v)"
                    :value="superAdminCompanyId"
                />
            </v-sheet>

            <v-divider></v-divider>

            <ListMenu :items="navMenu" />
        </v-navigation-drawer>
        <v-app-bar app>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>{{ titlePage }} </v-toolbar-title>
            <v-progress-linear
                :active="loading"
                :indeterminate="loading"
                absolute
                bottom
                color="primary darken-2"
            ></v-progress-linear>
        </v-app-bar>
    </div>
</template>

<script>
import { getProfile } from "~/helpers/system";
import Logo from "~/components/Logo";
import { navMenu } from "~/config/nav";
import ListMenu from "./Menu";
import CompanySelect from "~/components/widgets/CompanySelect";
import { mapGetters } from "vuex";

export default {
    components: {
        Logo,
        ListMenu,
        CompanySelect,
    },
    data() {
        return {
            drawer: null,
            showNav: false,
            loading: false,
            navMenu,
        };
    },
    methods: {},
    watch: {
        userInfo: {
            immediate: true,
            handler(val) {
                this.showNav = !!val;
            },
        },
    },
    computed: {
        hasUserInfo() {
            return Object.keys(this.userInfo).length > 0;
        },
        userInfo() {
            return this.$store.state.session.info || {};
        },
        name() {
            return `${this.userInfo.first_name} ${this.userInfo.middle_name} ${this.userInfo.last_name}`;
        },

        titlePage() {
            return this.$route.meta?.appTitle;
        },
        ...mapGetters("session", ["isSuperAdmin"]),

        superAdminCompanyId: {
            get() {
                if (!this.isSuperAdmin) return {};

                return this.$store.state.session.superAdminCompanyId;
            },
            set({ id }) {
                this.$store.dispatch("session/setSuperAdminCompanyId", id);
            },
        },
    },

    created() {
        getProfile();
    },
    mounted() {
        this.$root.$on("pageLoading", (val = true) => {
            this.loading = val;
        });
    },
};
</script>

<style>
.v-toolbar__extension {
    padding: 0 !important;
}
</style>
