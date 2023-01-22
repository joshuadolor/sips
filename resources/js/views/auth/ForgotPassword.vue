<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center class="elevation-3">
            <v-flex xs12 sm8 md3>
                <div class="text-center mb-5">
                    <img
                        :src="logo"
                        class="shrink mr-auto ml-left"
                        width="250"
                    />
                </div>
                <v-form
                    @submit.prevent="submit"
                    :loading="loading"
                    v-model="isValid"
                    ref="form"
                >
                    <v-text-field
                        prepend-icon="mdi-account"
                        label="Email"
                        type="text"
                        :rules="emailRules"
                        v-model="email"
                    ></v-text-field>
                    <v-btn type="submit" block class="my-3" color="primary"
                        >Submit</v-btn
                    >
                    <v-row>
                        <v-col cols="6">
                            <v-btn
                                @click="$router.push({ name: 'login' })"
                                block
                                text
                                >Login</v-btn
                            >
                        </v-col>
                        <v-col cols="6">
                            <v-btn
                                @click="$router.push({ name: 'register' })"
                                block
                                text
                                color="secondary"
                                >Register</v-btn
                            >
                        </v-col>
                    </v-row>
                </v-form>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import AuthService from "@/services/AuthService";
const logo = require("@/assets/logo.png");

export default {
    name: "ForgotPasswordPage",
    data() {
        return {
            logo,
            email: "",
            loading: false,
            isValid: false,
            emailRules: [
                (v) => !!v || "Email is required",
                (v) => /.+@.+/.test(v) || "Email must be valid",
            ],
        };
    },
    methods: {
        async submit() {
            this.$refs.form.validate();
            if (!this.isValid) return;

            this.loading = true;
            const request = {
                email: this.email,
                password: this.password,
            };

            try {
                await AuthService.forgotPassword(request);
                this.$refs.form.reset();
            } catch (e) {
                const err =
                    e?.response?.data?.error?.message || "Invalid Credentials";
                this.$root.$emit("showSnackbar", err);
                console.log({ e });
            }

            this.loading = false;
        },
    },
};
</script>
