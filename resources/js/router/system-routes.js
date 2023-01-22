import ClientStorage from "~/utils/Storage";

export const routes = [
    {
        path: "/",
        redirect: { name: "login-page" },
    },
    {
        path: "/logout",
        name: "logout",
        meta: {
            noWrapper: true,
        },
        beforeEnter: (to, from, next) => {
            ClientStorage.set("accessToken", null);
            Store.dispatch("session/setInfo", null);
            window.location = "/";
        },
    },
    {
        path: "*",
        name: "not-found",
        meta: {
            noWrapper: true,
        },
        component: () =>
            import(/* webpackChunkName: "notFound" */ "../views/NotFound.vue"),
    },
];
