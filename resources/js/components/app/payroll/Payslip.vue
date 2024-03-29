<template>
    <v-card class="pa-4" flat>
        <v-card-text>
            <table>
                <tr>
                    <td>
                        <div class="font-weight-bold text-body-1">
                            Employee Information
                        </div>
                        <div>Name: {{ name }}</div>
                        <div>Employee Code: {{ item.employee_code }}</div>
                    </td>
                    <td class="text-right" style="vertical-align: top">
                        <h1 class="primary--text">PAYSLIP</h1>
                        <div>Payroll #{{ payrollId }}</div>
                    </td>
                </tr>
            </table>

            <div class="my-5 text-center">
                <h3>{{ company }}</h3>
                <div class="text-caption">
                    Payroll Period: {{ dateFrom }} - {{ dateUntil }}
                </div>
            </div>

            <table class="mb-10 bordered">
                <thead class="grey lighten-2">
                    <tr>
                        <th class="text-left">Earnings</th>
                        <th class="text-right">Hours</th>
                        <th class="text-right">Rate</th>
                        <th class="text-right">Current</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Standard Pay</td>
                        <td class="text-right">{{ currency(item.hours) }}</td>
                        <td class="text-right">{{ currency(item.rate) }}</td>
                        <td class="text-right">{{ currency(grossPay) }}</td>
                    </tr>
                </tbody>
                <tfoot class="grey lighten-2">
                    <tr>
                        <th width="75%" class="text-right" colspan="3">
                            Gross Pay
                        </th>
                        <th class="text-right">{{ currency(grossPay) }}</th>
                    </tr>
                </tfoot>
            </table>

            <table class="my-10 bordered">
                <thead class="grey lighten-2">
                    <tr>
                        <th class="text-left">Deductions</th>
                        <th class="text-right">Current</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">Deduction</td>
                        <td class="text-right">{{ currency(deduction) }}</td>
                    </tr>
                </tbody>
                <tfoot class="grey lighten-2">
                    <tr>
                        <th width="75%" class="text-right">Total Deductions</th>
                        <th class="text-right">{{ currency(deduction) }}</th>
                    </tr>
                </tfoot>
            </table>

            <table class="mt-10 bordered">
                <thead class="grey lighten-2">
                    <tr>
                        <th width="75%" class="text-right">Net Pay</th>
                        <th class="font-weight-bold text-right text-body-1">
                            {{ currency(netPay) }}
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="d-block text-center text-caption">
                {{ createdInfo }}
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import { currency } from "~/helpers";
import { format } from "date-fns";

export default {
    methods: {
        currency,
    },
    props: {
        item: {},
    },
    computed: {
        name() {
            return this.item.employee_name;
        },
        payrollId() {
            return this.item.id.toString().padStart(7, "0");
        },
        grossPay() {
            return parseFloat(this.item.hours) * parseFloat(this.item.rate);
        },
        deduction() {
            return parseFloat(this.item.deduction);
        },
        dateFrom() {
            return format(new Date(this.item.date_from), "MMMM d, Y");
        },
        dateUntil() {
            return format(new Date(this.item.date_until), "MMMM d, Y");
        },
        netPay() {
            return this.grossPay - this.deduction;
        },
        createdInfo() {
            const datetime = format(
                new Date(this.item.created_at),
                "MMMM d, Y hh:mm:ss"
            );
            const { first_name, last_name, middle_name, email } =
                this.item.created_by;
            const creator = `${last_name}, ${first_name} ${middle_name} (${email})`;

            return `Created ${datetime} by ${creator}`;
        },
        company() {
            return this.$store.state.session.info?.company?.name;
        },
    },
};
</script>

<style lang="scss" scoped>
table {
    width: 100%;
    &.bordered {
        border: 1px solid #ccc;
    }

    td,
    th {
        padding: 5px 10px;
    }
}
</style>

<style lang="scss">
@media print {
    body * {
        visibility: hidden;
        font-family: "Roboto";
    }
    #printable * {
        visibility: visible;
    }
    #printable {
        width: 100%;
        position: absolute;
        left: 0;
        top: 0px;

        .primary--text {
            color: #1976d2 !important;
        }
        .my-10 {
            margin: 20px 0px;
        }
        .mt-10 {
            margin-top: 20px;
        }

        .my-5 {
            margin: 15px 0px;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .grey {
            background: #000;
        }

        .text-body-1 {
            font-weight: bold;
            font-size: 1.1em;
        }

        .text-center {
            text-align: center;
        }

        .text-caption {
            font-size: 0.8em;
            opacity: 0.8;
        }
    }
}
</style>
