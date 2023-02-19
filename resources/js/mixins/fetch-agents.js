import Service from "~/services/ResourceService";

export default {
    data() {
        return {
            agents: [],
            loading: false,
            agentsFetching: false,
        };
    },
    methods: {
        async fetchAgents() {
            this.agentsFetching = true;
            try {
                this.agents = await Service.get("agents");
                this.$emit("fetch-done");
            } catch (exception) {
                const message =
                    exception.getMessage() || "Something went wrong";
                this.$root.$emit("showSnackbar", message, "red");
            }
            this.agentsFetching = false;
        },
    },
    computed: {
        company_id() {
            return this.$store.state.session.info.company_id;
        },
    },
    mounted() {
        this.fetchAgents();
    },
};
