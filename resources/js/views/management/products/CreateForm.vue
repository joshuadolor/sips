<template>
    <v-form
        @submit.prevent="submit"
        :disabled="isSubmitting"
        ref="form"
        v-model="formValid"
    >
        <v-text-field
            label="Name*"
            v-model="name"
            :rules="getRules(name, ['required'], 'Product Name', 'name')"
        ></v-text-field>
        <v-text-field
            label="Quantity"
            v-model="quantity"
            type="number"
        ></v-text-field>
        <v-text-field
            label="Price"
            v-model="price"
            type="number"
            :rules="getRules(price, ['required'], 'Price', 'price')"
        ></v-text-field>
        <v-text-field
            label="Item Code"
            v-model="item_code"
            readonly
            :rules="getRules(item_code, ['required'], 'Item Code', 'item_code')"
        >
            <template v-slot:append>
                <v-btn text @click="generateCode"> Generate </v-btn>
            </template>
        </v-text-field>

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
import { generateRandomString } from "~/helpers";
import BaseCreateFrom from "~/components/forms/BaseCreateForm";

const formData = {
    name: "",
    price: "",
    quantity: 0,
    item_code: generateRandomString(8, "ITM-"),
};

export default {
    name: "CreateProductForm",
    extends: BaseCreateFrom,
    data() {
        return {
            ...formData,
            formData,
            formKeys: Object.keys(formData),
            resourceTerm: "products",
            dataExceptions: ["company_id"],
        };
    },
    methods: {
        generateCode() {
            this.item_code = generateRandomString(8, "ITM-");
        },
    },
};
</script>

<style></style>
