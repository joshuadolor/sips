import BaseException from "~/services/BaseService";

class AuthService extends BaseException {
    static endpoint = "/auth";

    static login(request) {
        return this.client
            .setUrl(`${this.endpoint}/login`)
            .post(request)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }

    static register(request) {
        return this.client
            .setUrl(`${this.endpoint}/register`)
            .post(request)
            .then(({ data }) => data);
    }

    static me() {
        return this.client
            .setUrl(`${this.endpoint}/me`)
            .get()
            .then(({ data }) => data);
    }

    static forgotPassword(request) {
        return this.client
            .setUrl(`${this.endpoint}/forgot-password`)
            .post(request)
            .then(({ data }) => data);
    }

    static changePassword(data) {
        return this.client
            .setUrl(`${this.endpoint}/profile/change-password`)
            .put(data)
            .then(({ data }) => data);
    }
}

export default AuthService;
