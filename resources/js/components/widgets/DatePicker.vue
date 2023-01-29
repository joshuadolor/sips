<template>
    <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        transition="scale-transition"
        offset-y
        min-width="auto"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                :value="dateDisplay"
                :label="label"
                prepend-inner-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                :rules="rules"
                v-on="on"
            ></v-text-field>
        </template>
        <v-date-picker
            v-model="date"
            :min="minDate"
            @change="save"
        ></v-date-picker>
    </v-menu>
</template>

<script>
export default {
    data: () => ({
        date: "",
        menu: false,
    }),
    watch: {
        menu(val) {
            val;
        },
    },
    methods: {
        save(date) {
            this.$refs.menu.save(date);
            this.$emit("input", this.date);
        },
    },
    computed: {
        dateDisplay() {
            return this.date;
        },
    },
    props: {
        label: {
            default: "Date",
        },
        minDate: {
            default: "2019-01-01",
        },
        rules: { default: () => [] },
    },
};
</script>
