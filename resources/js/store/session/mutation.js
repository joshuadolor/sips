import { accessTokenKey } from "./actions";
import ClientStorage from "~/utils/Storage";

export default {
    setAccessToken(state, token = null) {
        ClientStorage.set(accessTokenKey, token);
        state.accessToken = token;
    },

    setInfo(state, data) {
        state.info = data;
    },

    setSuperAdminCompanyId(state, data) {
        state.superAdminCompanyId = data;
    },
};
