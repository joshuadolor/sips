import BaseService from "~/services/BaseService";

class ProductMovementService extends BaseService {
    static endpoint = "/product-movements";

    static onHand(params = {}) {
        return this.client
            .setUrl(this.endpoint + "/report/on-hand")
            .get(params)
            .then(({ data }) => data)
            .catch((e) => {
                throw this.createException(e);
            });
    }
}

export default ProductMovementService;
