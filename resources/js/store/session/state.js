import { accessTokenKey } from "./actions";
import ClientStorage from "~/utils/Storage";

export default {
    accessToken: ClientStorage.get(accessTokenKey),
    info: {},
    superAdminCompanyId: {},
};
