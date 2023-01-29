<template>
    <v-form :disabled="isSubmitting" ref="form" v-model="formValid">
        <v-text-field
            label="Name*"
            v-model="name"
            :rules="getRules(name, ['required'], 'Name', 'name')"
        ></v-text-field>

        <v-container class="mt-3">
            <v-row>
                <v-col class="text-right">
                    <v-btn :disabled="isSubmitting" @click="$emit('close')" text
                        >cancel</v-btn
                    >
                    <v-btn
                        :loading="isSubmitting"
                        color="primary"
                        @click="submit"
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
    name: "",
};

export default {
    name: "CreateCompanyForm",
    extends: BaseCreateFrom,
    data() {
        return {
            ...formData,
            formData,
            formKeys: Object.keys(formData),
            resourceTerm: "companies",
        };
    },
};
</script>
