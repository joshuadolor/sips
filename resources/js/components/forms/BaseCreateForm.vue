<script>
import ResourceFormMixin from "~/mixins/resource-form";

const formData = {};

export default {
    name: "BaseCreateForm",
    mixins: [ResourceFormMixin],
    data() {
        return {
            ...formData,
            formData,
            formKeys: Object.keys(formData),
            resourceTerm: "b-a-s-e",
            dataExceptions: [],
        };
    },
    methods: {
        setDefaults() {
            Object.keys(formData).forEach((key) => {
                try {
                    if (!this.dataExceptions.includes(key)) {
                        this[key] = this.formData[key];
                    }
                } catch (e) {}
            });
        },
    },
};
</script>

<style></style>
