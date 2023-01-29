<template>
    <v-form
        @submit.prevent="submit"
        :disabled="isSubmitting"
        ref="form"
        v-model="formValid"
    >
        <v-text-field
            label="First Name*"
            v-model="first_name"
            :rules="
                getRules(first_name, ['required'], 'First Name', 'first_name')
            "
        ></v-text-field>
        <v-text-field
            label="Last Name*"
            v-model="last_name"
            :rules="getRules(last_name, ['required'], 'Last Name', 'last_name')"
        ></v-text-field>
        <v-text-field label="Middle Name" v-model="middle_name"></v-text-field>

        <v-container class="mt-3">
            <v-row>
                <v-col class="text-right">
                    <v-btn :disabled="isSubmitting" @click="$emit('close')" text
                        >cancel</v-btn
                    >
                    <v-btn
                        :loading="isSubmitting"
                        color="primary"
                        elevation="0"
                        type="submit"
                        >Submit</v-btn
                    >
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
import BaseCreateFrom from "~/components/forms/BaseCreateForm";

const formData = {
    first_name: "",
    middle_name: "",
    last_name: "",
};

export default {
    name: "CreateEmployeeForm",
    extends: BaseCreateFrom,
    data() {
        return {
            ...formData,
            formData,
            formKeys: Object.keys(formData),
            resourceTerm: "agents",
            dataExceptions: ["company_id"],
        };
    },
};
</script>

<style></style>
