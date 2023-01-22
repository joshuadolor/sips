<template>
    <v-list>
        <v-list-group
            v-for="item in items"
            :key="item.text"
            :no-action="hasSubmenu(item) || !item.routeName"
            :append-icon="hasSubmenu(item) ? 'mdi-chevron-down' : null"
        >
            <template v-slot:activator>
                <v-list-item
                    color="primary"
                    :inactive="hasSubmenu(item)"
                    :to="{ name: item.routeName }"
                >
                    <v-list-item-icon>
                        <v-icon v-text="item.icon"></v-icon>
                    </v-list-item-icon>
                    <v-list-item-title> {{ item.text }}</v-list-item-title>
                </v-list-item>
            </template>

            <v-list-group
                :key="submenuItem.text"
                v-for="submenuItem in item.submenu"
                sub-group
                prepend-icon=""
            >
                <template v-slot:activator>
                    <v-list-item
                        color="primary"
                        :to="{ name: submenuItem.routeName }"
                    >
                        <v-list-item-icon>
                            <v-icon v-text="submenuItem.icon"></v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>
                            {{ submenuItem.text }}</v-list-item-title
                        >
                    </v-list-item>
                </template>

                <!--
                    for 3rd-level 
                    <v-list-item
                    v-for="([text, icon], i) in submenuItem"
                    :key="i"
                    link
                >
                    <v-list-item-title v-text="text"></v-list-item-title>

                    <v-list-item-icon>
                        <v-icon v-text="icon"></v-icon>
                    </v-list-item-icon>
                </v-list-item> -->
            </v-list-group>
        </v-list-group>
    </v-list>
</template>

<script>
export default {
    name: "NavMenu",
    props: ["items"],
    methods: {
        hasSubmenu(node) {
            return (node?.submenu || []).length > 0;
        },
    },
    computed: {
        routeName() {
            return this.$route.name;
        },
    },
};
</script>

<style>
.v-list-item--active::before {
    background: transparent !important;
}
</style>
