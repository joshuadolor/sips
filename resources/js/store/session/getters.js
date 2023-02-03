export default {
    roleName(state) {
        const roleMap = ["user", "admin", "user-admin"];
        const role = state.info.role;
        return roleMap[role] ?? roleMap[0];
    },
    companyId() {
        return state.info?.company_id;
    },
};
