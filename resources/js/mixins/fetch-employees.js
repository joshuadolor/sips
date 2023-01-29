import Service from "~/services/ResourceService";

export default {
    data() {
        return {
            employees: [],
            loading: false,
            employeesFetching: false,
        };
    },
    methods: {
        mapProperties(employee) {
            return employee;
        },
        async fetchEmployees() {
            this.employeesFetching = true;
            try {
                const employees = await Service.get("employees");
                employees.map(this.mapProperties);
                this.employees = employees;
            } catch (exception) {
                const message =
                    exception.getMessage() || "Something went wrong";
                this.$root.$emit("showSnackbar", message, "red");
            }
            this.employeesFetching = false;
        },
    },
    computed: {
        company_id() {
            return this.$store.state.session.info.company_id;
        },
    },
    mounted() {
        this.fetchEmployees();
    },
};
