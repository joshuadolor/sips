<template>
    <v-app>
        <NavigationDrawer v-if="hasSession" />
        <v-main app>
            <v-container class="pa-4" v-if="!noWrapper">
                <router-view />
            </v-container>
            <router-view v-else />
        </v-main>
        <v-snackbar :color="snackbarColor" v-model="snackbar">
            {{ snackbarMessage }}
        </v-snackbar>
    </v-app>
</template>

<script>
import NavigationDrawer from "~/components/Nav";

export default {
    name: "App",
    components: {
        NavigationDrawer,
    },
    data: () => ({
        snackbarColor: "",
        snackbar: false,
        snackbarMessage: null,
    }),
    computed: {
        currentPage() {
            return this.$route.meta;
        },
        noWrapper() {
            return this.currentPage["noWrapper"];
        },
        hasSession() {
            return !!this.$store.state.session.accessToken;
        },
    },
    methods: {
        async fetchDefaults() {},
        showMessage(msg, color = null) {
            this.snackbarColor = color;
            this.snackbar = true;
            this.snackbarMessage = msg;
        },
    },
    async mounted() {
        this.$root.$on("showSnackbar", (msg, color) => {
            this.showMessage(msg, color);
        });

        const params = new URLSearchParams(location.search);

        if (params.get("app-message")) {
            this.$root.$emit("showSnackbar", params.get("app-message"));
            this.$router.replace({ query: null });
        }

        await this.fetchDefaults();
    },
};
</script>

<style>
.container {
    padding: 0px !important;
}
</style>
