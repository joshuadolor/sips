<template>
    <v-container>
        <v-row>
            <v-col>
                <Payslip />
            </v-col>
        </v-row>
        <v-row>
            <v-col class="text-right">
                <v-btn @click="$emit('close')" text>Close</v-btn>
                <v-btn color="primary" @click="print">Print</v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import Payslip from "./Payslip.vue";
import printJs from "print-js";

export default {
    name: "PrintPreview",
    components: {
        Payslip,
    },
    methods: {
        print() {
            printJs("printable", "html");
        },
    },
};
</script>

<style></style>
