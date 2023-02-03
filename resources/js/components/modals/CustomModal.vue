<template>
    <v-dialog @input="modalToggle" v-model="dialog" :width="width">
        <template v-slot:activator="{ on, attrs }">
            <v-btn v-bind="btnAttrs" dark v-on="on" v-show="!btnAttrs.hide">
                <v-icon v-if="btnAttrs.actualIcon">{{
                    btnAttrs.actualIcon
                }}</v-icon>
                {{ btnLabel }}
            </v-btn>
        </template>
        <v-card>
            <v-card-title>
                {{ title }}
            </v-card-title>
            <v-card-text>
                <slot :close="close"></slot>
            </v-card-text>
            <v-card-actions>
                <slot name="actions" :close="close"></slot>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: "CustomModal",
    data() {
        return {
            dialog: false,
            isModal: true,
        };
    },
    props: {
        btnLabel: {
            default: "<Button Label>",
        },
        title: {
            default: "<Custom Modal title>",
        },
        btnAttrs: {
            default: () => ({
                color: "primary",
            }),
        },
        width: {
            default: 450,
        },
    },
    methods: {
        close() {
            this.dialog = false;
        },
        open() {
            this.dialog = true;
        },
        modalToggle(value) {
            this.$emit("modal-toggle", value);
        },
    },
};
</script>

<style></style>
