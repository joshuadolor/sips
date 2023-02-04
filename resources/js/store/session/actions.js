export const accessTokenKey = "accessToken";

export default {
    setAccessToken({ commit }, data) {
        commit("setAccessToken", data);
    },
    setInfo({ commit }, data) {
        commit("setInfo", data);
    },
    setSuperAdminCompanyId({ commit }, data) {
        commit("setSuperAdminCompanyId", data);
    },
};
