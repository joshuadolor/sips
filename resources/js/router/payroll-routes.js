export const routes = [
    {
        path: "/payroll",
        name: "payroll",
        component: () =>
            import(/* webpackChunkName: "payroll" */ "~/views/payroll"),
        meta: {
            appTitle: "Payroll",
        },
    },
    {
        path: "/payroll/{id}",
        name: "payroll.details",
        component: () =>
            import(/* webpackChunkName: "payroll-details" */ "~/views/payroll"),
        meta: {
            appTitle: "Payroll Details",
        },
    },
];
