import ExceptionFactory from "~/factories/exception-factory";
import Client from "~/utils/ApiClientV1";

export default class BaseService {
    static factory = ExceptionFactory;
    static client = Client;

    static createException(e) {
        return this.factory.make(e);
    }

    static get(params = {}) {
        return this.client
            .setUrl(this.endpoint)
            .get(params)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }

    static create(request) {
        return this.client
            .setUrl(`${this.endpoint}`)
            .post(request)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }
}
