<template>
    <v-container>
        <v-form
            @submit.prevent="submit"
            :disabled="isSubmitting"
            ref="form"
            v-model="formValid"
        >
            <v-row>
                <v-col cols="12" xs="12" sm="12" md="6">
                    <DatePicker
                        label="Payroll Period From"
                        v-model="date_from"
                        :rules="
                            getRules(
                                date_from,
                                ['required'],
                                'Payroll Period From',
                                'date_from'
                            )
                        "
                    />
                </v-col>
                <v-col cols="12" xs="12" sm="12" md="6">
                    <DatePicker
                        label="Payroll Period Until"
                        v-model="date_until"
                        :minDate="date_from"
                        :rules="
                            getRules(
                                date_until,
                                ['required'],
                                'Payroll Period Until',
                                'date_until'
                            )
                        "
                    />
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" xs="12" sm="12" md="4">
                    <v-autocomplete
                        label="Employee Code"
                        :items="employees"
                        v-model="employee"
                        item-text="employee_code"
                        :rules="[
                            (v) =>
                                employee.length > 0 ||
                                'Employee should be atleast one',
                        ]"
                        multiple
                        return-object
                    >
                    </v-autocomplete>
                </v-col>
                <v-col>
                    <v-autocomplete
                        label="Employee"
                        :items="employees"
                        v-model="employee"
                        item-text="full_name"
                        multiple
                        return-object
                        :rules="[
                            (v) =>
                                employee.length > 0 ||
                                'Employee should be atleast one',
                        ]"
                    >
                    </v-autocomplete>
                </v-col>
            </v-row>

            <CreateTable
                v-if="employee.length > 0"
                :employees="employee"
                ref="table"
            />

            <v-container class="mt-3">
                <v-row>
                    <v-col class="text-right">
                        <v-btn
                            :disabled="isSubmitting"
                            @click="$emit('close')"
                            text
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
    </v-container>
</template>

<script>
import BaseCreateFrom from "~/components/forms/BaseCreateForm";
import { currency } from "~/helpers";

const formData = {
    date_from: "",
    date_until: "",
    rate: "",
    hours: "",
    deduction: "0",
};
import fetchEmployees from "~/mixins/fetch-employees";
import DatePicker from "~/components/widgets/DatePicker";

const getDateDifference = (date1, date2) => {
    // Convert both dates to milliseconds
    const date1InMs = date1.getTime();
    const date2InMs = date2.getTime();

    // Calculate the difference in milliseconds
    const differenceInMs = Math.abs(date2InMs - date1InMs);

    // Convert milliseconds to days
    return Math.floor(differenceInMs / (1000 * 60 * 60 * 24));
};
import ResourceService from "~/services/ResourceService";
import CreateTable from "./CreateTable";

export default {
    name: "CreatePayrollForm",
    extends: BaseCreateFrom,
    mixins: [fetchEmployees],
    components: {
        DatePicker,
        CreateTable,
    },
    data() {
        return {
            ...formData,
            formData,
            formKeys: Object.keys(formData),
            resourceTerm: "payroll",
            dataExceptions: ["company_id"],

            employee: [],
        };
    },
    methods: {
        currency,
        minimumZero(field) {
            if (this[field] === "") {
                this[field] = 0;
            }
        },
        customRequestData() {
            return {
                company_id: this.company_id,
                employee_code: this.employee_code,
                employee_name: this.employee_name,
                employee_id: this.employee_id,
            };
        },
        async submit() {
            this.apiErrors = {};
            this.attemptSubmit();
            if (!this.dataForSubmission) {
                return;
            }
            const data = this.$refs.table.rows;

            if (
                !data.every((d) => {
                    return d.hours > 0 && d.rate > 0;
                })
            ) {
                return;
            }

            this.isSubmitting = true;

            try {
                await Promise.all(
                    data.map(async (emp) => {
                        const req = {
                            company_id: emp.company_id,
                            employee_code: emp.employee_code,
                            employee_name: emp.full_name,
                            employee_id: emp.id,
                            ...this.formKeys.reduce(
                                (a, b) => ({ ...a, [b]: this[b] }),
                                {}
                            ),
                            hours: emp.hours,
                            rate: emp.rate,
                            deduction: emp.deduction,
                        };
                        return await ResourceService.create("payroll", req);
                    })
                );

                this.$root.$emit("showSnackbar", this.successMessage);
                this.$emit("success", {});

                this.resetForm();
                this.resetValidation();
            } catch (exception) {
                const message = exception.getMessages();
                this.apiErrors = message;
            }

            this.isSubmitting = false;
        },
    },
    computed: {
        employee_code() {
            return this.employee.map((emp) => emp.employee_code);
        },
        employee_name() {
            return this.employee.map((emp) => emp.full_name);
        },
        employee_id() {
            return this.employee.map((emp) => emp.id);
        },
        days() {
            if (!this.date_from || !this.date_until) {
                return 0;
            }
            const fromDate = new Date(this.date_from);
            const untilDate = new Date(this.date_until);

            return getDateDifference(fromDate, untilDate);
        },
        subTotal() {
            return this.rate * this.hours;
        },
        total() {
            return this.subTotal - this.deduction;
        },
        company_id() {
            if (this.isSuperAdmin) {
                return this.$store.state.session.superAdminCompanyId;
            }
            return this.$store.state.session.info.company_id;
        },
    },
};
</script>

<style></style>
