<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="4" sm="6" xs="12">
                <InfoCard />
            </v-col>
            <v-col cols="12" md="4" sm="6" xs="12">
                <ChangePasswordCard />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import InfoCard from "~/components/app/profile/Info";
import ChangePasswordCard from "~/components/app/profile/ChangePassword";
export default {
    components: {
        ChangePasswordCard,
        InfoCard,
    },
};
</script>

<style></style>
