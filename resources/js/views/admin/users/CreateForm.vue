<template>
    <v-form
        @submit.prevent="submit"
        :disabled="isSubmitting"
        ref="form"
        v-model="isValid"
    >
        <v-text-field
            required
            prepend-icon="mdi-account"
            v-model="first_name"
            label="First Name"
            :rules="
                getRules(first_name, ['required'], 'First Name', 'first_name')
            "
            type="text"
        ></v-text-field>
        <v-text-field
            required
            prepend-icon="mdi-account1"
            v-model="middle_name"
            label="Middle Name"
            type="text"
        ></v-text-field>
        <v-text-field
            required
            prepend-icon="mdi-account1"
            v-model="last_name"
            label="Last Name"
            :rules="getRules(last_name, ['required'], 'Last Name', 'last_name')"
            type="text"
        ></v-text-field>
        <v-text-field
            required
            prepend-icon="mdi-email"
            v-model="email"
            label="Email"
            type="text"
            name="email"
            :rules="
                getRules(email, ['required', 'emailValid'], 'Email', 'email')
            "
        ></v-text-field>

        <v-switch v-model="role" :value="1">
            <template v-slot:label>
                Role: {{ role == 1 ? "Admin" : "Normal User" }}
            </template>
        </v-switch>

        <CompanySelect prependIcon="mdi-domain" v-model="company" />

        <v-container class="mt-3">
            <v-row>
                <v-col class="text-right">
                    <v-btn :disabled="isSubmitting" @click="$emit('close')" text
                        >cancel</v-btn
                    >
                    <v-btn
                        :loading="isSubmitting"
                        color="primary"
                        type="submit"
                        elevation="0"
                        >Submit</v-btn
                    >
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
const formData = {
    first_name: "",
    middle_name: "",
    last_name: "",
    email: "",
    company_id: "",
    role: 0,
};
import FormComponent from "~/mixins/form-component";
import CompanySelect from "~/components/widgets/CompanySelect";

export default {
    data() {
        return {
            ...formData,
            isSubmitting: false,
            isValid: true,
            formData,
        };
    },
    components: {
        CompanySelect,
    },
    mixins: [FormComponent],
    methods: {
        async submit() {
            if (!this.isValid) {
                return;
            }
            console.log("submit");
        },
    },
};
</script>

<style></style>
