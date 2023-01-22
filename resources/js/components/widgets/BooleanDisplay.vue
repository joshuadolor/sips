<template>
    <v-icon :color="color">
        {{ icon }}
    </v-icon>
</template>

<script>
export default {
    name: "BooleanDisplay",
    props: {
        value: { default: false },
    },
    computed: {
        icon() {
            return this.value ? "mdi-check-circle" : "mdi-close-circle";
        },
        color() {
            return this.value ? "green" : "gray";
        },
    },
};
</script>

<style></style>
