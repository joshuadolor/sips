export default {
    props: ["value"],
    data() {
        return {
            watchProps: [],
        };
    },
    computed: {
        internalValue: {
            get() {
                return this.value;
            },
            set(value) {
                this.handleValueUpdate(value);

                this.$emit("input", value);
            },
        },
    },
    watch: {
        value: {
            deep: true,
            immediate: true,
            handler(newVal) {
                this.handleValueUpdate(newVal);
            },
        },
    },
    methods: {
        handleValueUpdate(newVal, oldVal) {
            if (typeof newVal !== "object") {
                return;
            }
            const newValKeys = Object.keys(newVal);
            if (newValKeys.length === 0) return;

            newValKeys.forEach((prop) => {
                this[prop] = newVal[prop];
            });
        },
        setWatchProps() {
            if (this.watchProps.length === 0) return;

            const getObjectProp = (vm) =>
                this.watchProps.reduce((a, b) => {
                    a[b] = vm[b];
                    return a;
                }, {});

            this.$watch(
                (vm) => getObjectProp(vm),
                (val) => {
                    this.internalValue = val;
                },
                {
                    immediate: true, // run immediately
                    deep: true, // detects changes inside objects. not needed here, but maybe in other cases
                }
            );
        },
    },
    created() {
        this.setWatchProps();
    },
};
