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
            v-model="selectedProducts"
            item-value="id"
            item-text="item_code"
            multiple
            :rules="[
                (v) =>
                    selectedProducts.length > 0 ||
                    'Item selected should be atleast one',
            ]"
            return-object
        ></v-autocomplete>
        <v-autocomplete
            :loading="productsFetching"
            :items="products"
            item-value="id"
            item-text="name"
            v-model="selectedProducts"
            label="Product Name"
            multiple
            :rules="[
                (v) =>
                    selectedProducts.length > 0 ||
                    'Product selected should be atleast one',
            ]"
            return-object
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

        <v-simple-table v-if="selectedProducts.length > 0">
            <template v-slot:default>
                <thead>
                    <tr>
                        <th class="text-left">Name</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in selectedProducts" :key="item.id">
                        <td>{{ item.name }}</td>
                        <td class="text-center">{{ quantity }}</td>
                        <td class="text-right">{{ currency(item.price) }}</td>
                        <td class="text-right">
                            {{ currency(quantity * item.price) }}
                        </td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>

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

            selectedProducts: [],
        };
    },
    methods: {
        currency,
        async submit() {
            this.attemptSubmit();
            if (!this.dataForSubmission) {
                return;
            }

            this.isSubmitting = true;

            try {
                const data = await Promise.all(
                    this.selectedProducts.map(async (product) => {
                        const req = {
                            type: this.type,
                            quantity: this.quantity,
                            agent_id: this.agent_id,
                            product_id: product.id,
                            cost: this.quantity * parseFloat(product.price),
                        };
                        return await Service.create(req);
                    })
                );

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
