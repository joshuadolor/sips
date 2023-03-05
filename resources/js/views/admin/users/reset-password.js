import Service from "~/services/AccountService";

export default {
    data() {
        return {
            resettingPassword: false,
        };
    },
    methods: {
        async resetPassword(id) {
            this.resettingPassword = true;
            try {
                const request = {
                    user_id: id,
                };
                const service = Service.resetPassword(request);
                const data = await service;

                this.$root.$emit("showSnackbar", "Password Reset Successful");
                this.$emit("reset-password-success", data);
            } catch (exception) {
                const message = exception.getMessages();
                this.apiErrors = message;
            }

            this.resettingPassword = false;
        },
    },
};
