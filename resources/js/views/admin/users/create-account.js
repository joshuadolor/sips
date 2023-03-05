import Service from "~/services/AccountService";

export default {
    data() {
        return {
            creatingAccount: false,
        };
    },
    methods: {
        async createUser() {
            this.creatingAccount = true;
            try {
                const request = {
                    user_id: id,
                };
                const service = Service.create(request);
                const data = await service;

                this.$root.$emit("showSnackbar", "Password Reset Successful");
                this.$emit("reset-password-success", data);
            } catch (exception) {
                const message = exception.getMessages();
                this.apiErrors = message;
            }

            this.creatingAccount = false;
        },
    },
};
