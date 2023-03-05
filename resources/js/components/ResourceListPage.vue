<template>
    <v-data-table
        @current-items="getFiltered"
        :items="items"
        :headers="headers"
    >
    </v-data-table>
</template>

<script>
import BooleanDisplay from "~/components/widgets/BooleanDisplay";
import ResourceService from "~/services/ResourceService";
import CustomModal from "~/components/modals/CustomModal";
import CSVCreator from "~/utils/CSVCreator";
import { format } from "date-fns";

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
            getParams: {},
            additionalHeaders: [
                {
                    text: "Actions",
                    value: "actions",
                },
            ],
            search: "",
            filteredItems: [],
            reportValues: {
                test: (val) => {
                    return val;
                },
            },
        };
    },
    methods: {
        saveReport() {
            const headers = this.rawHeaders.map((header) => header.value);
            const displayHeaders = this.rawHeaders.map((header) => header.text);
            const data = this.filteredItems.map((item) => {
                return headers.reduce((a, b) => {
                    const value = this.reportValues[b]
                        ? this.reportValues[b](item)
                        : item[b];

                    return { ...a, [b]: value };
                }, {});
            });

            const csv = new CSVCreator({
                headers,
                data,
                displayHeaders,
            });

            const date = format(new Date(), "Y_MM_dd_HH_mm_ss");

            const fileName = this.resourceName + "_" + date;
            csv.export(fileName);
        },
        getFiltered(items) {
            this.filteredItems = items;
        },
        async fetchData() {
            this.$root.$emit("pageLoading");
            try {
                this.items = await this.service.get(
                    this.resourceTerm,
                    this.getParams
                );
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
