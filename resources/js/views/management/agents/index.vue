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
                    <v-btn
                        :disabled="items.length < 1"
                        @click="saveReport"
                        color="warning darken-2"
                    >
                        <v-icon class="mr-2">mdi-download</v-icon>
                        Download Report</v-btn
                    >
                    <v-text-field
                        prepend-inner-icon="mdi-magnify"
                        v-model="search"
                        placeholder="Search"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-container>

        <v-data-table
            @current-items="getFiltered"
            :items="items"
            :headers="headers"
        >
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
                    value: "first_name",
                    text: "First Name",
                },
                {
                    value: "last_name",
                    text: "Last Name",
                },
                {
                    value: "middle_name",
                    text: "Middle Name",
                },
            ],
            resourceTerm: "agents",
            createModalAttrs: {
                title: "Create Agent/Store",
                btnLabel: "Create New",
            },
            updateModalAttrs: {
                title: "Update Agent/Store",
                btnLabel: "Update",
                btnAttrs: {
                    color: "amber darken-2",
                    hide: true,
                },
            },
            resourceName: "Agents/Stores",
        };
    },
};
</script>
