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
            :items="processedProducts"
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
            :items="processedProducts"
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

        <v-autocomplete
            label="Agent"
            :items="agents"
            v-model="agent_id"
            item-value="id"
            item-text="full_name"
            :loading="agentsFetching"
            :rules="getRules(agent_id, ['required'], 'Agent', 'agent_id')"
        ></v-autocomplete>

        <v-simple-table fixed-header v-if="selectedProducts.length > 0">
            <template v-slot:default>
                <thead>
                    <tr>
                        <th class="text-left">Name</th>
                        <th class="text-center">
                            <v-checkbox v-model="sameQuantities" dense>
                                <template v-slot:label>
                                    <small v-if="!sameQuantities"
                                        >Quantity</small
                                    >
                                </template>
                            </v-checkbox>
                            <v-text-field
                                v-if="sameQuantities"
                                label="Quantity"
                                dense
                                min="1"
                                type="number"
                                v-model="lastQuantityValue"
                            >
                            </v-text-field>
                        </th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, idx) in selectedProducts" :key="item.id">
                        <td>{{ item.name }}</td>
                        <td class="text-center">
                            <v-text-field
                                type="number"
                                min="1"
                                v-model="item.toProcessQuantity"
                                :max="item.quantity"
                                hide-details="auto"
                                label="Quantity"
                                :disabled="sameQuantities"
                                @input="
                                    updateQuantity(
                                        item.toProcessQuantity,
                                        item.id
                                    )
                                "
                            />
                        </td>

                        <td class="text-right">{{ currency(item.price) }}</td>
                        <td class="text-right">
                            {{
                                currency(
                                    parseInt(item.toProcessQuantity) *
                                        parseFloat(item.price)
                                )
                            }}
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
import CreateInventoryModule from "~/views/inventory/CreateForm";
import Service from "~/services/ProductMovementService";

export default {
    name: "CreateSales",
    extends: CreateInventoryModule,
    data() {
        return {
            type: "sales",
        };
    },
    methods: {
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
                            quantity: product.toProcessQuantity,
                            agent_id: this.agent_id,
                            product_id: product.id,
                            cost:
                                parseInt(product.toProcessQuantity) *
                                parseFloat(product.price),
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
