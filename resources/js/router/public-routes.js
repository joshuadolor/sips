import ClientStorage from "~/utils/Storage";

import LoginPage from "~/views/auth/Login";
import RegisterPage from "~/views/auth/Register";

const authCheck = (to, from, next) => {
    const hasToken = ClientStorage.get("accessToken");
    if (hasToken) {
        next({ name: "profile-page" });
    } else {
        next();
    }
};

export const routes = [
    {
        path: "/login",
        name: "login-page",
        component: LoginPage,
    },
    {
        path: "/register",
        name: "register-page",
        component: RegisterPage,
    },
].map((route) => {
    route.beforeEnter = authCheck;
    route.meta = {
        noWrapper: true,
    };
    return route;
});
