import BaseService from "~/services/BaseService";

class AccountsService extends BaseService {
    static endpoint = "/account";

    static changePassword(request) {
        return this.client
            .setUrl(`${this.endpoint}/change-password`)
            .post(request)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }

    static resetPassword(request) {
        return this.client
            .setUrl(`${this.endpoint}/reset-password`)
            .post(request)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }
}

export default AccountsService;
