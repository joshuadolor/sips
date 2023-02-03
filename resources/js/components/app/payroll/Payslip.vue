<template>
    <v-container id="printable">
        <v-row>
            <v-col>
                <v-list>
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title>EMPL-88881231</v-list-item-title>
                            <v-list-item-subtitle
                                >Employee Code</v-list-item-subtitle
                            >
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item two-line>
                        <v-list-item-content>
                            <v-list-item-title
                                >Dela Cruz, Juan Olbes</v-list-item-title
                            >
                            <v-list-item-subtitle>Name</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-col>
            <v-col>
                <v-simple-table>
                    <tbody>
                        <tr v-for="(item, idx) in items" :key="idx">
                            <td>{{ item.label }}</td>
                            <td>{{ item.value }}</td>
                        </tr>
                    </tbody>
                </v-simple-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "Payslip",
    data() {
        return {
            items: [{ label: "Days" }],
        };
    },
};
</script>

<style scoped>
#printable {
    border: 1px solid black;
}
</style>
