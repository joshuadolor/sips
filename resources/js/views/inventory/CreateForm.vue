<template>
    <v-form
        @submit.prevent="submit"
        :disabled="isSubmitting"
        ref="form"
        v-model="formValid"
    >
        <v-autocomplete
            :loading="productsFetching"
            label="Item Code"
            :items="products"
            v-model="product_id"
            item-value="id"
            item-text="item_code"
            :rules="
                getRules(product_id, ['required'], 'Item Code', 'product_id')
            "
        ></v-autocomplete>
        <v-autocomplete
            :loading="productsFetching"
            :items="products"
            item-value="id"
            item-text="name"
            v-model="product_id"
            label="Product Name"
            :rules="
                getRules(product_id, ['required'], 'Product Name', 'product_id')
            "
        ></v-autocomplete>
        <v-switch
            true-value="receive"
            false-value="transfer"
            inset
            v-model="type"
            :label="`Type: ${type === 'receive' ? 'Receive' : 'Transfer'}`"
        ></v-switch>

        <v-autocomplete
            label="Agent"
            :items="agents"
            v-model="agent_id"
            item-value="id"
            item-text="full_name"
            :loading="agentsFetching"
            :rules="getRules(agent_id, ['required'], 'Agent', 'agent_id')"
        ></v-autocomplete>

        <v-text-field
            label="Quantity"
            v-model="quantity"
            type="number"
            :rules="[
                ...getRules(quantity, ['required'], 'Quantity', 'quantity'),
                (v) => v > 0 || 'Quantity must be greater than 0',
            ]"
        ></v-text-field>

        <div class="font-weight-bold" v-if="selectedProduct">
            Cost: {{ currency(cost) }}
        </div>

        <v-container class="mt-3">
            <v-row>
                <v-col class="text-right">
                    <v-btn :disabled="isSubmitting" @click="$emit('close')" text
                        >cancel</v-btn
                    >
                    <v-btn
                        :loading="isSubmitting"
                        color="primary"
                        type="submit"
                        elevation="0"
                        >Submit</v-btn
                    >
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
import BaseCreateFrom from "~/components/forms/BaseCreateForm";
import Service from "~/services/ProductMovementService";
import FetchProductsMixin from "~/mixins/fetch-products";
import FetchAgentsMixin from "~/mixins/fetch-agents";
import { currency } from "~/helpers";

const formData = {
    type: "receive",
    quantity: "",
    agent_id: "",
    product_id: "",
};

export default {
    name: "ReceiveTransferProducts",
    extends: BaseCreateFrom,
    mixins: [FetchAgentsMixin, FetchProductsMixin],
    data() {
        return {
            ...formData,
            formData,
            formKeys: Object.keys(formData),
            resourceTerm: "products",
            dataExceptions: ["company_id"],
            service: Service,
        };
    },
    computed: {
        selectedProduct() {
            return this.products.find(
                (product) => product.id === this.product_id
            );
        },
        cost() {
            if (!this.selectedProduct) return 0;

            return this.selectedProduct.price * this.quantity;
        },
    },
    methods: {
        currency,
        async submit() {
            this.attemptSubmit();
            if (!this.dataForSubmission) {
                return;
            }

            const request = {
                ...this.formKeys.reduce((a, b) => ({ ...a, [b]: this[b] }), {}),
                cost: this.cost,
            };
            this.isSubmitting = true;
            try {
                const data = await Service.create(request);
                this.$root.$emit("showSnackbar", this.successMessage);
                this.$emit("success", data);
            } catch (exception) {
                console.log(exception);
                const message = exception.getMessages();
                this.apiErrors = message;
            }
            this.isSubmitting = false;
        },
    },
};
</script>

<style></style>
