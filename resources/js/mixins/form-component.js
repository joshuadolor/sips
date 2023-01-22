import * as rules from "~/helpers/rules";

const getAllChild = (component, childForms = []) => {
    const children = component.$children;
    childForms = [
        ...childForms,
        ...children.filter((comp) => comp.isFormComponent),
    ];

    for (let i = 0; i < children.length; i++) {
        childForms = [...childForms, ...getAllChild(children[i])];
    }

    return childForms;
};

export default {
    data() {
        return {
            validations: {},
            formValid: true,
            isFormComponent: true,
            formReference: "form",
            apiErrors: {},
            dataForSubmission: true,
        };
    },
    methods: {
        validate() {
            this.dataForSubmission = true;
        },
        getRules(value, givenRules = [], fieldName, serviceFieldName = null) {
            const errors = givenRules.map((gr) => {
                if (!rules[gr]) throw `Rule: ${gr} - not implemented`;
                return rules[gr](value, fieldName);
            });

            serviceFieldName = serviceFieldName ?? fieldName;
            const apiError = this.apiErrors[serviceFieldName] ?? [];
            return [...errors, ...apiError];
        },

        getRuleMessage(
            value,
            givenRules = [],
            fieldName,
            serviceFieldName = null
        ) {
            const rules = this.getRules(
                value,
                givenRules,
                fieldName,
                serviceFieldName
            );

            if (rules.length > 0) return rules[0];

            return "";
        },

        validateForm() {
            if (this.$refs[this.formReference]?.validate) {
                this.$refs[this.formReference].validate();
            }
            //on concrete component
            this.validate();

            this.scrollToError();
        },
        scrollToError() {
            const field = document.querySelector(".error--text");
            if (field) {
                field.scrollIntoViewIfNeeded();
            }
        },
        resetForm() {
            this.$refs[this.formReference].reset();
        },
        resetValidation() {
            this.$refs[this.formReference].resetValidation();
        },

        getAllChildForms() {
            return getAllChild(this);
        },

        attemptSubmit() {
            const childForms = this.getAllChildForms();
            this.dataForSubmission = true;

            childForms.forEach((form) => {
                form.validateForm();
                if (!form.formValid) {
                    console.log("not valid", form.$options.name);
                }
                this.dataForSubmission *= form.formValid;
            });
        },
    },
};
