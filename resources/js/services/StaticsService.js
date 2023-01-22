import Client from "@/utils/ApiClientV1";

class StaticService {
    static endpoint = "/app";

    static get() {
        return Client.setUrl(`${this.endpoint}`)
            .get()
            .then(({ data }) => data);
    }

    static update(request) {
        return Client.setUrl(`${this.endpoint}`)
            .put(request)
            .then(({ data }) => data);
    }
}

export default StaticService;
