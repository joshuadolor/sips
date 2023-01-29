export const routes = [
    {
        path: "/profile",
        name: "profile-page",
        component: () =>
            import(
                /* webpackChunkName: "profile-page" */ "~/views/account/Profile.vue"
            ),
        meta: {
            appTitle: "Profile",
        },
    },
];
