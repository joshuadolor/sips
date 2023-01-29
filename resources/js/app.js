require("./bootstrap");

import App from "~/App.vue";
import vuetify from "~/config/vuetify";
import router from "~/router";
import store from "~/store";
import Vue from "vue";

const app = new Vue({
    vuetify,
    router,
    store,
    render: (h) => h(App),
});

window.Store = store;
window.App = app;
app.$mount("#app");
