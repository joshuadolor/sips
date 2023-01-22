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
            </v-sheet>

            <v-divider></v-divider>

            <ListMenu :items="links" />
        </v-navigation-drawer>
        <v-app-bar app>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>{{ titlePage }}</v-toolbar-title>
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
import { navMenu } from "~/config";
import ListMenu from "./Menu";

export default {
    components: {
        Logo,
        ListMenu,
    },
    data() {
        return {
            drawer: null,
            showNav: false,
            rawLinks: navMenu,
            loading: false,
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
        links() {
            const userRoles = this.userInfo.roles || [];
            return this.rawLinks.filter((link) => {
                return true;
                return (link?.permitted || []).every((l) =>
                    userRoles.includes(l)
                );
            });
        },
        titlePage() {
            return this.$route.meta?.appTitle;
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
