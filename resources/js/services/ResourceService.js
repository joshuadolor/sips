import BaseService from "./BaseService";

class ResourceService extends BaseService {
    static endpoint = "/resource";

    static get(targetResource) {
        return this.client
            .setUrl(this.endpoint)
            .setUrl(`${this.endpoint}/${targetResource}`)
            .get()
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }
}

export default ResourceService;
