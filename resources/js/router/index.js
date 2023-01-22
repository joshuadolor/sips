import Vue from "vue";
import VueRouter from "vue-router";
import ClientStorage from "~/utils/Storage";
Vue.use(VueRouter);

import { routes as publicRoutes } from "./public-routes";
import { routes as accountRoutes } from "./account-routes";
import { routes as managementRoutes } from "./management-routes";
import { routes as reportRoutes } from "./reports-routes";
import { routes as adminRoutes } from "./admin-routes";
import { routes as systemRoutes } from "./system-routes";

const routes = [
    ...publicRoutes,
    ...accountRoutes,
    ...managementRoutes,
    ...reportRoutes,
    ...adminRoutes,
    ...systemRoutes,
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL || "",
    routes,
});

router.beforeEach(async (to, from, next) => {
    const hasToken = ClientStorage.get("accessToken");
    const exemptedRoutes = [
        "login-page",
        "register-page",
        "not-found",
        "forgot-password",
        "logout",
    ];

    if (!hasToken && !exemptedRoutes.includes(to.name)) {
        next({ name: "login-page" });
        return;
    }

    next();
});

export default router;
