export const routes = [
    {
        path: "/sales-entry",
        name: "sales",
        component: () =>
            import(/* webpackChunkName: "sales" */ "~/views/sales"),
        meta: {
            appTitle: "Sales Entry",
        },
    },
    {
        path: "/inventory",
        name: "inventory",
        component: () =>
            import(/* webpackChunkName: "inventory" */ "~/views/inventory"),
        meta: {
            appTitle: "Inventory",
        },
    },
];
