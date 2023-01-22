<template>
    <v-dialog v-model="dialog" width="450">
        <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary" dark v-bind="attrs" v-on="on">
                {{ btnLabel }}
            </v-btn>
        </template>
        <v-card>
            <v-card-title>
                {{ title }}
            </v-card-title>
            <v-card-text>
                <slot></slot>
            </v-card-text>
            <v-card-actions>
                <slot name="actions"></slot>
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
        };
    },
    props: {
        btnLabel: {
            default: "Create",
        },
        title: {
            default: "Create",
        },
    },
};
</script>

<style></style>
