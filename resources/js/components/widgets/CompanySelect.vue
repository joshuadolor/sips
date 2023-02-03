<template>
    <v-autocomplete
        item-text="name"
        item-value="id"
        :items="companies"
        :label="label"
        v-model="company"
        :loading="companiesFetching"
        v-if="isSuperAdmin"
    >
    </v-autocomplete>
</template>

<script>
import { mapGetters } from "vuex";
import CustomComponent from "~/mixins/custom-component";
import FetchCompanies from "~/mixins/fetch-companies";

export default {
    name: "CompanySelect",
    mixins: [CustomComponent, FetchCompanies],
    data() {
        return {
            company: "",
        };
    },
    computed: {
        ...mapGetters("session", ["isSuperAdmin"]),
    },
    props: {
        label: {
            default: "Company",
        },
    },
    mounted() {
        this.$on("fetch-done", () => {
            if (this.companies.length > 0) {
                this.company = this.companies[0];
            }
        });
    },
};
</script>

<style></style>
