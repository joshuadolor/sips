export const routes = [
    {
        path: "/reports",
        name: "reports",
        component: () =>
            import(/* webpackChunkName: "reports" */ "~/views/reports"),
    },
];
