import { links as adminLinks } from "./admin-links";
export const navMenu = [
    {
        icon: "mdi-account-circle",
        text: "Profile",
        routeName: "profile-page",
    },

    {
        icon: "mdi-tag-text",
        text: "Sales Entry",
        routeName: "sales",
    },
    {
        icon: "mdi-text-box-edit",
        text: "Inventory",
        routeName: "inventory",
    },
    {
        icon: "mdi-card-account-details",
        text: "Payroll",
        routeName: "payroll",
    },
    {
        icon: "mdi-format-list-bulleted-square",
        text: "Management",
        routeName: "management-pagex",
        role: ["admin"],
        submenu: [
            {
                icon: "mdi-account-hard-hat",
                text: "Employees",
                routeName: "management.employees",
            },
            {
                icon: "mdi-bottle-wine",
                text: "Products",
                routeName: "management.products",
            },
            {
                icon: "mdi-target-account",
                text: "Agents",
                routeName: "management.agents",
            },
        ],
    },
    {
        icon: "mdi-book-open",
        text: "Reports",
        routeName: "reports",
        role: ["admin"],
    },
    {
        icon: "mdi-account-star",
        routeName: "admin-pagex",
        text: "Admin",
        role: ["super-admin"],
        submenu: [...adminLinks],
    },
    {
        icon: "mdi-logout",
        text: "Logout",
        routeName: "logout",
    },
];
