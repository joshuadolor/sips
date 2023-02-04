<template>
    <div>
        <v-container>
            <v-row>
                <v-col>
                    <h1 class="d-inline">{{ resourceName }}</h1>
                </v-col>
                <v-col align-self="center" class="text-right">
                    <CustomModal
                        v-slot:default="{ close }"
                        v-bind="createModalAttrs"
                    >
                        <CreateForm
                            @success="
                                close();
                                fetchData();
                            "
                            @close="close"
                        />
                    </CustomModal>
                </v-col>
            </v-row>
        </v-container>

        <v-data-table :items="items" :headers="headers">
            <template v-slot:item.actions="{ item }">
                <v-btn
                    small
                    elevation="0"
                    color="amber darken-2"
                    @click="openUpdateModal(item)"
                    dark
                    >Update</v-btn
                >
            </template>
        </v-data-table>
        <CustomModal
            ref="updateModal"
            v-slot:default="{ close }"
            v-bind="updateModalAttrs"
        >
            <UpdateForm
                @success="
                    close();
                    fetchData();
                "
                :currentData="toUpdate"
                @close="close"
            />
        </CustomModal>
    </div>
</template>

<script>
import ResourceListPage from "~/components/ResourceListPage";
import CreateForm from "./CreateForm";
import UpdateForm from "./UpdateForm";

export default {
    name: "AgentsManagement",
    extends: ResourceListPage,
    components: {
        CreateForm,
        UpdateForm,
    },
    data() {
        return {
            rawHeaders: [
                {
                    value: "created_at",
                    text: "Created At",
                },
                {
                    value: "name",
                    text: "Name",
                },
            ],
            resourceTerm: "companies",
            createModalAttrs: {
                title: "Create Company",
                btnLabel: "Create New",
            },
            updateModalAttrs: {
                title: "Update Compant",
                btnLabel: "Update",
                btnAttrs: {
                    color: "amber darken-2",
                    hide: true,
                },
            },
            resourceName: "Companies",
        };
    },
};
</script>
