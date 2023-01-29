<template>
    <v-data-table :items="items" :headers="headers"> </v-data-table>
</template>

<script>
import BooleanDisplay from "~/components/widgets/BooleanDisplay";
import ResourceService from "~/services/ResourceService";
import CustomModal from "~/components/modals/CustomModal";
export default {
    name: "ResourceListPage",
    components: {
        BooleanDisplay,
        CustomModal,
    },
    data() {
        return {
            rawHeaders: [],
            columnMap: [],
            items: [],
            service: ResourceService,
            resourceTerm: "",
            toUpdate: {},
            additionalHeaders: [
                {
                    text: "Actions",
                    value: "actions",
                },
            ],
            search: "",
        };
    },
    methods: {
        async fetchData() {
            this.$root.$emit("pageLoading");
            try {
                this.items = await this.service.get(this.resourceTerm);
            } catch (exception) {
                const message = exception.getMessage() || "Invalid Credentials";
                this.$root.$emit("showSnackbar", message, "red");
            }
            this.$root.$emit("pageLoading", false);
        },
        showBooleanState(row, column) {
            if (row[column]) {
                return "yup activated";
            }
            return "not activated";
        },
        openUpdateModal(item) {
            this.$refs.updateModal.open();
            this.toUpdate = item;
        },
    },
    computed: {
        headers() {
            return [
                ...this.rawHeaders,
                //import actions component to be used on subclass components
                ...this.additionalHeaders,
            ];
        },
    },
    mounted() {
        if (!this.resourceTerm) {
            console.error("Resource term not set");
            return;
        }
        this.fetchData();
    },
};
</script>

<style></style>
