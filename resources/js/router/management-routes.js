export const routes = [
    {
        path: "/management/employees",
        name: "management.employees",
        component: () =>
            import(
                /* webpackChunkName: "management.employees" */ "~/views/management/employees"
            ),
        meta: {
            appTitle: "Employees",
        },
    },
    {
        path: "/management/products",
        name: "management.products",
        component: () =>
            import(
                /* webpackChunkName: "management.products" */ "~/views/management/products"
            ),
        meta: {
            appTitle: "Products",
        },
    },
    {
        path: "/management/agents",
        name: "management.agents",
        component: () =>
            import(
                /* webpackChunkName: "management.agents" */ "~/views/management/agents"
            ),
        meta: {
            appTitle: "Agents",
        },
    },
];
