import Service from "~/services/ResourceService";

export default {
    data() {
        return {
            products: [],
            loading: false,
            productsFetching: false,
        };
    },
    methods: {
        async fetchProducts() {
            this.productsFetching = true;
            try {
                this.products = await Service.get("products");
                this.$emit("fetch-done");
            } catch (exception) {
                const message =
                    exception.getMessage() || "Something went wrong";
                this.$root.$emit("showSnackbar", message, "red");
            }
            this.productsFetching = false;
        },
    },
    computed: {
        company_id() {
            return this.$store.state.session.info.company_id;
        },
    },
    mounted() {
        this.fetchProducts();
    },
};
