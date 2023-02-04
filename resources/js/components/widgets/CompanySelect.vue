<template>
    <v-autocomplete
        item-text="name"
        item-value="id"
        :items="processedCompanies"
        :label="label"
        v-model="internalValue"
        :loading="companiesFetching"
        return-object
    >
    </v-autocomplete>
</template>

<script>
import CustomComponent from "~/mixins/custom-component";
import FetchCompanies from "~/mixins/fetch-companies";

export default {
    name: "CompanySelect",
    mixins: [CustomComponent, FetchCompanies],

    computed: {
        processedCompanies() {
            return this.companies;
        },
    },
    props: {
        label: {
            default: "Company",
        },
        appendEmpty: {
            default: false,
        },
    },
    mounted() {
        this.$on("fetch-done", () => {
            if (this.companies.length > 0) {
                this.internalValue = this.companies[0];
            }
        });
    },
};
</script>

<style></style>
