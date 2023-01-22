import Vuex from "vuex";
import Vue from "vue";
Vue.use(Vuex);

import session from "~/store/session";

export default new Vuex.Store({
    modules: {
        session,
    },
});
