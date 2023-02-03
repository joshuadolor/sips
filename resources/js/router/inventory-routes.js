export const routes = [
    {
        path: "/sales-entry",
        name: "sales",
        component: () =>
            import(/* webpackChunkName: "sales" */ "~/views/admin/companies"),
        meta: {
            appTitle: "Sales Entry",
        },
    },
    {
        path: "/inventory",
        name: "inventory",
        component: () =>
            import(
                /* webpackChunkName: "inventory" */ "~/views/admin/Users.vue"
            ),
        meta: {
            appTitle: "Inventory",
        },
    },
];
