<template>
    <v-simple-table fixed-header v-if="rows.length > 0">
        <template v-slot:default>
            <thead>
                <tr>
                    <th class="text-left">Employee</th>
                    <th class="text-center">
                        <v-checkbox v-model="sameHours" dense>
                            <template v-slot:label>
                                <small v-if="!sameHours">Hours</small>
                            </template>
                        </v-checkbox>
                        <v-text-field
                            v-if="sameHours"
                            label="Hours"
                            min="1"
                            dense
                            type="number"
                            v-model="lastHoursValue"
                        >
                        </v-text-field>
                    </th>
                    <th class="text-right">
                        <v-checkbox v-model="sameRate" dense>
                            <template v-slot:label>
                                <small v-if="!sameRate">Rate</small>
                            </template>
                        </v-checkbox>
                        <v-text-field
                            v-if="sameRate"
                            label="Rate"
                            min="1"
                            dense
                            type="number"
                            v-model="lastRateValue"
                        >
                        </v-text-field>
                    </th>
                    <th class="text-right">
                        <v-checkbox v-model="sameDeduction" dense>
                            <template v-slot:label>
                                <small v-if="!sameDeduction">Deduction</small>
                            </template>
                        </v-checkbox>
                        <v-text-field
                            v-if="sameDeduction"
                            label="Deduction"
                            min="1"
                            dense
                            type="number"
                            v-model="lastRateValue"
                        >
                        </v-text-field>
                    </th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, idx) in rows" :key="item.id">
                    <td>
                        <small>{{ item.employee_code }}</small>
                        <br />
                        {{ item.full_name }}
                    </td>
                    <td class="text-center">
                        <v-text-field
                            type="number"
                            min="1"
                            v-model="rows[idx].hours"
                            hide-details="auto"
                            label="Hours"
                            :disabled="sameHours"
                            @input="updateValue('hours', item.hours, item.id)"
                        />
                    </td>
                    <td class="text-center">
                        <v-text-field
                            type="number"
                            min="1"
                            v-model="rows[idx].rate"
                            hide-details="auto"
                            label="Rate"
                            :disabled="sameRate"
                            @input="updateValue('rate', item.rate, item.id)"
                        />
                    </td>
                    <td class="text-center">
                        <v-text-field
                            type="number"
                            min="1"
                            v-model="rows[idx].deduction"
                            hide-details="auto"
                            label="Deduction"
                            :disabled="sameDeduction"
                            @input="
                                updateValue(
                                    'deduction',
                                    item.deduction,
                                    item.id
                                )
                            "
                        />
                    </td>
                    <td class="text-right">
                        {{ currency(item.hours * item.rate - item.deduction) }}
                    </td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
    <!-- <v-row>
                <v-col cols="12" xs="12" sm="12" md="4">
                    <v-text-field
                        v-model="hours"
                        label="Hours*"
                        type="number"
                        @keyup1="minimumZero('hours')"
                        :rules="[
                            ...getRules(hours, ['required'], 'Hours', 'hours'),
                            (v) => v > 0 || 'Hours should be greater than 0',
                        ]"
                    ></v-text-field>
                </v-col>
                <v-col cols="12" xs="12" sm="12" md="4">
                    <v-text-field
                        v-model="rate"
                        label="Rate*"
                        @keyup1="minimumZero('rate')"
                        type="number"
                        :rules="[
                            ...getRules(rate, ['required'], 'Rate', 'rate'),
                            (v) => v > 0 || 'Rate should be greater than 0',
                        ]"
                    ></v-text-field>
                </v-col>
                <v-col cols="12" xs="12" sm="12" md="4">
                    <v-text-field
                        label="Deduction"
                        v-model="deduction"
                        @keyup1="minimumZero('deduction')"
                        type="number"
                    ></v-text-field>
                </v-col>
            </v-row> -->
</template>

<script>
import { currency } from "~/helpers";

export default {
    name: "CreatePayrollTable",
    props: ["employees"],
    data() {
        return {
            sameHours: false,
            sameRate: false,
            sameDeduction: false,

            lastHoursValue: 1,
            lastRateValue: 1,
            lastDeductionValue: 0,

            rows: [],
        };
    },
    methods: {
        currency,
        updateValue(col, value, id) {
            this.rows = this.rows.map((row) => {
                if (row.id === id) {
                    row[col] = value;
                }

                return row;
            });
        },
    },
    watch: {
        employees: {
            handler(val) {
                this.rows = val.map((row) => {
                    row.hours = this.lastHoursValue;
                    row.deduction = this.lastDeductionValue;
                    row.rate = this.lastRateValue;

                    return row;
                });
            },
            deep: true,
            immediate: true,
        },
        lastHoursValue(val) {
            if (!val) return;

            this.rows = this.rows.map((row) => {
                row.hours = val;
                return row;
            });
        },
        lastDeductionValue(val) {
            if (!val) return;

            this.rows = this.rows.map((row) => {
                row.deduction = val;
                return row;
            });
        },
        lastRateValue(val) {
            if (!val) return;

            this.rows = this.rows.map((row) => {
                row.rate = val;
                return row;
            });
        },
    },
};
</script>

<style></style>
