import Service from "~/services/ResourceService";

export default {
    data() {
        return {
            togglingUser: false,
        };
    },
    methods: {
        async toggleUser() {
            this.togglingUser = true;
            try {
                const request = {
                    is_active: this.toggleUserActive ? 0 : 1,
                };
                const service = Service.update(
                    "users",
                    this.toggleUserData.id,
                    request
                );
                const data = await service;

                const message = `User ${
                    this.toggleUserActive ? "Deactivated" : "Activated"
                }`;

                this.$root.$emit("showSnackbar", message);
                this.$emit("toggle-user-success", data);
            } catch (exception) {
                console.log(exception);
                const message = exception.getMessages();
                this.apiErrors = message;
            }

            this.togglingUser = false;
        },
    },
    computed: {
        toggleUserActive() {
            return this.toggleUserData.is_active;
        },
        toggleUserData() {
            return this.toUpdate;
        },
    },
};
