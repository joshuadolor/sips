<script>
import CreateForm from "./CreateForm.vue";
import AccountService from "~/services/AccountService";

export default {
    extends: CreateForm,
    props: {
        defaultData: {
            default: () => {},
        },
    },
    data() {
        return {
            successMessage: "User updated",
        };
    },
    watch: {
        defaultData: {
            immediate: true,
            deep: true,
            handler(val) {
                [...Object.keys(this.formData), "company"].forEach((key) => {
                    this[key] = val[key];
                });
            },
        },
    },
    methods: {
        async submit() {
            if (!this.isValid) {
                return;
            }
            this.isSubmitting = true;

            try {
                const id = this.defaultData.id;

                const request = {};
                Object.keys(this.formData).forEach((key) => {
                    request[key] = this[key];
                });
                request.company_id = this.company.id;

                const data = await AccountService.update(id, request);

                this.$emit("close");

                this.$root.$emit("showSnackbar", this.successMessage);
                this.$emit("success", data);
            } catch (exception) {
                console.log(exception);
                const message = exception.getMessages();
                this.apiErrors = message;
            }

            this.isSubmitting = false;
        },
    },
};
</script>

<style></style>
