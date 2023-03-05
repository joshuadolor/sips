<template>
    <v-card>
        <v-form
            :disabled="loading"
            ref="form"
            v-model="formValid"
            @submit.prevent="submit"
        >
            <v-card-title>Change Password</v-card-title>
            <v-card-text>
                <v-text-field
                    type="password"
                    label="Current Password"
                    v-model="currentPassword"
                    :rules="
                        getRules(
                            currentPassword,
                            ['required'],
                            'Current Password',
                            'current_password'
                        )
                    "
                ></v-text-field>
                <v-text-field
                    type="password"
                    v-model="newPassword"
                    label="New Password"
                    :rules="
                        getRules(
                            currentPassword,
                            ['required'],
                            'New Password',
                            'new_password'
                        )
                    "
                ></v-text-field>
                <v-text-field
                    v-model="newConfirmPassword"
                    type="password"
                    label="Confirm New Password"
                    :rules="[
                        ...getRules(
                            newConfirmPassword,
                            ['required'],
                            'Confirm New Password',
                            'new_confirm_password'
                        ),
                        (v) =>
                            v === newPassword ||
                            'Confirm password must be the same with the set new Password',
                    ]"
                ></v-text-field>
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn
                    type="submit"
                    :loading="loading"
                    elevation="0"
                    color="primary"
                    >Submit</v-btn
                >
            </v-card-actions>
        </v-form>
    </v-card>
</template>

<script>
import FormComponent from "~/mixins/form-component";
import Service from "~/services/AccountService";

export default {
    name: "ChangePassword",
    mixins: [FormComponent],
    data() {
        return {
            loading: false,
            currentPassword: "",
            newPassword: "",
            newConfirmPassword: "",
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
            this.loading = true;

            try {
                const req = {
                    current_password: this.currentPassword,
                    new_password: this.newPassword,
                    new_confirm_password: this.newConfirmPassword,
                };
                const data = await Service.changePassword(req);

                this.$root.$emit("showSnackbar", "Password Changed!");
                this.$emit("success", data);

                this.resetForm();
                this.resetValidation();
            } catch (exception) {
                const message = exception.getMessages();
                this.apiErrors = message;
            }

            this.loading = false;
        },
    },
};
</script>

<style></style>
