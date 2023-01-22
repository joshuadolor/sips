import AccountService from "~/services/AccountService";
import ClientStorage from "~/utils/Storage";

export async function getProfile() {
    try {
        const data = await AccountService.get();
        Store.dispatch("session/setInfo", data);
    } catch (e) {
        if (e.errorCode === 401) {
            ClientStorage.set("accessToken", null);
            Store.dispatch("session/setInfo", null);
            window.location = "/";
        }
    }
}
