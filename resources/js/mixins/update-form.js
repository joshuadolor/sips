export default {
    data() {
        return {
            dataExceptions: ["company_id"],
        };
    },
    methods: {
        setDefaults() {
            this.$nextTick(() => {
                Object.keys(this.currentData).forEach((key) => {
                    try {
                        if (!this.dataExceptions.includes(key)) {
                            this[key] = this.currentData[key];
                        }
                    } catch (e) {}
                });
            });
        },
    },
    watch: {
        currentData: {
            deep: true,
            handler() {
                this.setDefaults();
            },
        },
    },
    props: {
        currentData: {
            default: () => ({}),
        },
    },
    computed: {
        resourceId() {
            return this.currentData.id;
        },
    },
};
