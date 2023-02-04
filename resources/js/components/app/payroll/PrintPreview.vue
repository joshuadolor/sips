<template>
    <v-container>
        <v-row>
            <v-col id="payslip">
                <Payslip :item="item" />
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

export default {
    name: "PrintPreview",
    components: {
        Payslip,
    },
    methods: {
        print() {
            const printContents = document.getElementById("payslip");

            const printable = document.createElement("div");
            printable.innerHTML = printContents.innerHTML;
            printable.id = "printable";

            const app = document.querySelector("body");

            app.append(printable);
            window.print();
            printable.remove();
        },
    },
    props: {
        item: {},
    },
};
</script>

<style scoped>
#payslip {
    border: 1px solid #ccc;
}
</style>
