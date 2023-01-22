<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md3>
                <div class="text-center mb-5">
                    <CompanyLogo />
                </div>
                <v-form
                    @submit.prevent="submit"
                    :disabled="loading"
                    ref="form"
                    v-model="isValid"
                >
                    <v-text-field
                        required
                        prepend-icon="mdi-account"
                        v-model="first_name"
                        label="First Name"
                        :rules="nameRules('First Name')"
                        type="text"
                    ></v-text-field>
                    <v-text-field
                        required
                        prepend-icon="mdi-account1"
                        v-model="middle_name"
                        label="Middle Name"
                        :rules="middleNameRules"
                        type="text"
                    ></v-text-field>
                    <v-text-field
                        required
                        prepend-icon="mdi-account1"
                        v-model="last_name"
                        label="Last Name"
                        :rules="nameRules('Last Name')"
                        type="text"
                    ></v-text-field>
                    <v-text-field
                        required
                        prepend-icon="mdi-email"
                        v-model="email"
                        label="Email"
                        type="text"
                        name="email"
                        :rules="emailRules"
                    ></v-text-field>
                    <v-text-field
                        required
                        prepend-icon="mdi-lock"
                        v-model="password"
                        label="Password"
                        :rules="passwordRule"
                        type="password"
                    ></v-text-field>
                    <v-text-field
                        required
                        prepend-icon="mdi-lock1"
                        v-model="confirm_password"
                        label="Confirm Password"
                        :rules="confirmPasswordRule"
                        type="password"
                    ></v-text-field>

                    <v-btn
                        type="submit"
                        :loading="loading"
                        block
                        class="my-3"
                        color="primary"
                        >Create Account</v-btn
                    >

                    <v-btn
                        text
                        block
                        v-if="!loading"
                        class="my-3"
                        color="secondary"
                        :to="{ name: 'login-page' }"
                        >Go to Login Page</v-btn
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
    name: "RegistrationPage",
    components: {
        CompanyLogo,
    },
    data() {
        return {
            first_name: "",
            middle_name: "",
            last_name: "",
            email: "",
            password: "",
            confirm_password: "",
            loading: false,
            isValid: false,
            confirmPasswordRule: [
                (v) =>
                    v === this.password ||
                    `Confirm password is different from the given password`,
            ],
            passwordRule: [
                (v) => !!v || `Password is required`,
                (v) =>
                    v.length > 6 || `Password must greater than 6 characters`,
            ],
            nameRules: (field) => [
                (v) => !!v || `${field} is required`,
                (v) =>
                    v.length <= 20 ||
                    `${field} must be less than 20 characters`,
            ],
            middleNameRules: [
                (v) =>
                    v.length <= 20 ||
                    "Middle Name must be less than 20 characters",
            ],
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
                first_name: this.first_name,
                middle_name: this.middle_name,
                last_name: this.last_name,
                email: this.email,
                password: this.password,
                confirm_password: this.confirm_password,
            };

            try {
                await AuthService.register(request);
                this.$refs.form.reset();
                window.location =
                    "/login?app-message=The admin will activate your account please wait or notify him/her.";
            } catch (e) {
                console.log(e);
            }

            this.loading = false;
        },
    },
};
</script>
