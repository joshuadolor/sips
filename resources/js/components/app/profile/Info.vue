<template>
    <v-card>
        <v-form>
            <v-card-title>Information</v-card-title>
            <v-card-text>
                <v-list-item two-line v-for="d in display" :key="d.label">
                    <v-list-item-content>
                        <v-list-item-title>
                            <span v-html="d.value"></span>
                        </v-list-item-title>
                        <v-list-item-subtitle>{{
                            d.label
                        }}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-card-text>
            <v-card-actions v-show="false">
                <v-spacer />
                <v-btn :loading="loading" elevation="0" color="primary"
                    >Submit</v-btn
                >
            </v-card-actions>
        </v-form>
    </v-card>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "Info",
    data() {
        return {
            loading: false,
        };
    },
    computed: {
        info() {
            return this.$store.state.session.info;
        },
        display() {
            return [
                {
                    label: "First Name",
                    value: this.info?.first_name,
                },
                {
                    label: "Last Name",
                    value: this.info?.last_name,
                },
                {
                    label: "Middle Name",
                    value: this.info?.middle_name,
                },
                {
                    label: "Email",
                    value: this.info?.email,
                },
                {
                    label: "Company",
                    value: this.info?.company?.name || "<i>NO COMPANY</i>",
                },
            ];
        },
    },
};
</script>

<style></style>
