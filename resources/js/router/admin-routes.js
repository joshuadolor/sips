export const routes = [
    {
        path: "/admin/users",
        name: "admin.users",
        component: () =>
            import(/* webpackChunkName: "admin.users" */ "~/views/admin/users"),
        meta: {
            appTitle: "App Users",
        },
    },
    {
        path: "/admin/companies",
        name: "admin.companies",
        component: () =>
            import(
                /* webpackChunkName: "admin.companies" */ "~/views/admin/companies"
            ),
        meta: {
            appTitle: "Companies",
        },
    },
];
