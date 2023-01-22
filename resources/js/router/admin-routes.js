export const routes = [
    {
        path: "/admin/users",
        name: "admin.users",
        component: () =>
            import(
                /* webpackChunkName: "admin.users" */ "~/views/admin/Users.vue"
            ),
    },
    {
        path: "/admin/companies",
        name: "admin.companies",
        component: () =>
            import(
                /* webpackChunkName: "admin.companies" */ "~/views/admin/Companies.vue"
            ),
    },
];
