export const routes = [
    {
        path: "/management/employees",
        name: "management.employees",
        component: () =>
            import(
                /* webpackChunkName: "management.employees" */ "~/views/management/employees"
            ),
    },
    {
        path: "/management/products",
        name: "management.products",
        component: () =>
            import(
                /* webpackChunkName: "management.products" */ "~/views/management/products"
            ),
    },
    {
        path: "/management/agents",
        name: "management.agents",
        component: () =>
            import(
                /* webpackChunkName: "management.agents" */ "~/views/management/agents"
            ),
    },
];
