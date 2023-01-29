<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center class="elevation-3">
            <v-flex xs10 sm8 md3>
                <div class="text-center mb-5">
                    <CompanyLogo />
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
                    <v-text-field
                        id="password"
                        v-model="password"
                        prepend-icon="mdi-lock"
                        label="Password"
                        type="password"
                        :rules="passwordRules"
                        autocomplete="current-password"
                    ></v-text-field>
                    <v-btn
                        type="submit"
                        :loading="loading"
                        block
                        class="my-3"
                        color="primary"
                        >Login</v-btn
                    >
                    <v-btn
                        @click="$router.push({ name: 'register-page' })"
                        block
                        text
                        :disabled="loading"
                        class="my-3"
                        color="secondary"
                        >Register</v-btn
                    >
                </v-form>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import AuthService from "~/services/AuthService";
import CompanyLogo from "~/components/Logo";

export default {
    name: "LoginPage",
    components: {
        CompanyLogo,
    },
    data() {
        return {
            email: "superadmin@mailinator.com",
            password: "password",
            loading: false,
            isValid: false,
            passwordRules: [(v) => !!v || `Password is required`],
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
                const { token, user } = await AuthService.login(request);
                this.$store.dispatch("session/setAccessToken", token);
                this.$store.dispatch("session/setInfo", user);

                this.$router.replace({ name: "profile-page" });
            } catch (exception) {
                const message = exception.getMessage() || "Invalid Credentials";
                this.$root.$emit("showSnackbar", message, "red");
            }

            this.loading = false;
        },
    },
};
</script>
