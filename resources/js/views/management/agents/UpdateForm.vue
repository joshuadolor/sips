<script>
import CreateForm from "./CreateForm";
import UpdateFormMixin from "~/mixins/update-form";

export default {
    name: "UpdateAgentForm",
    extends: CreateForm,
    mixins: [UpdateFormMixin],
    data() {
        return {
            dataExceptions: ["company_id"],
            serviceMethod: "update",
            successMessage: "Updated",
            serviceParams: ["resourceTerm", "resourceId", "requestData"],
        };
    },
};
</script>
