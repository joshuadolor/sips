import Service from "~/services/ResourceService";

export default {
    data() {
        return {
            companies: [],
            loading: false,
            companiesFetching: false,
        };
    },
    methods: {
        async fetchCompanies() {
            this.companiesFetching = true;
            try {
                this.companies = await Service.get("companies");
                this.$emit("fetch-done");
            } catch (exception) {
                const message =
                    exception.getMessage() || "Something went wrong";
                this.$root.$emit("showSnackbar", message, "red");
            }
            this.companiesFetching = false;
        },
    },
    computed: {
        company_id() {
            return this.$store.state.session.info.company_id;
        },
    },
    mounted() {
        this.fetchCompanies();
    },
};
