<script>
import CreateForm from "./CreateForm";
import UpdateFormMixin from "~/mixins/update-form";

export default {
    name: "UpdateCompanyForm",
    extends: CreateForm,
    mixins: [UpdateFormMixin],
    data() {
        return {
            serviceMethod: "update",
            successMessage: "Updated",
            serviceParams: ["resourceTerm", "resourceId", "requestData"],
        };
    },
};
</script>
