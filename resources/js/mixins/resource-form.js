import { mapGetters } from "vuex";
import FormComponent from "~/mixins/form-component";
import Service from "~/services/ResourceService";

const formData = {
    //will contain key value pair of the form
};

export default {
    mixins: [FormComponent],
    data() {
        return {
            ...formData,
            resourceTerm: "t-e-r-m",
            isSubmitting: false,
            formKeys: [
                //put keys here
                //Object.keys(formData)
            ],
            serviceMethod: "create",
            successMessage: "Created",
            serviceParams: ["resourceTerm", "requestData"],
        };
    },
    methods: {
        async submit() {
            this.apiErrors = {};
            this.attemptSubmit();
            if (!this.dataForSubmission) {
                return;
            }
            this.isSubmitting = true;
            try {
                const params = this.serviceParams.map((param) => {
                    return this[param];
                });
                const service = Service[this.serviceMethod].apply(
                    Service,
                    params
                );
                const data = await service;

                this.$root.$emit("showSnackbar", this.successMessage);
                this.$emit("success", data);

                this.resetForm();
                this.resetValidation();
            } catch (exception) {
                console.log(exception);
                const message = exception.getMessages();
                this.apiErrors = message;
            }

            this.isSubmitting = false;
        },

        customRequestData() {
            return {
                company_id: this.company_id,
            };
        },

        setDefaults() {},
        getParentModal(component) {
            const parent = component.$parent;
            if (parent.isModal) {
                return parent;
            }

            if (parent === this.$root) {
                return false;
            }

            return this.getParentModal(parent);
        },
    },
    computed: {
        ...mapGetters("session", ["isSuperAdmin", "companyId"]),
        requestData() {
            let data = {};
            this.formKeys.forEach((key) => {
                data[key] = this[key];
            });

            data = { ...data, ...this.customRequestData() };

            return data;
        },
        company_id() {
            if (this.isSuperAdmin) {
                return this.$store.state.session.superAdminCompanyId;
            }
            return this.companyId;
        },
    },
    mounted() {
        this.setDefaults();
    },
};
