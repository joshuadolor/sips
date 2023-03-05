import BaseService from "./BaseService";

class ResourceService extends BaseService {
    static endpoint = "/resource";

    static get(targetResource, getParams = {}) {
        return this.client
            .setUrl(`${this.endpoint}/${targetResource}`)
            .get(getParams)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }

    static create(targetResource, request) {
        return this.client
            .setUrl(`${this.endpoint}/${targetResource}`)
            .post(request)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }

    static update(targetResource, id, request) {
        return this.client
            .setUrl(`${this.endpoint}/${targetResource}/${id}`)
            .put(request)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }
}

export default ResourceService;
