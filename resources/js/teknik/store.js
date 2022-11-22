export default {
    state: {
        loading: false,
        produkLampiran: [],
    },
    mutations: {
        setLoading(state, data) {
            state.loading = data;
        },

        setProdukLampiran(state, data) {
            state.produkLampiran = data;
        },

        deleteProdukLampiran(state, data) {
            state.produkLampiran.splice(data, 1);
        },

        setProdukLampiran(state, data) {
            state.produkLampiran = data;
        },
    },
    actions: {
        setLoading({ commit }, data) {
            commit('setLoading', data);
        },

        setProdukLampiran({ commit }, data) {
            commit('setProdukLampiran', data);
        },

        deleteProdukLampiran({ commit }, data) {
            commit('deleteProdukLampiran', data);
        },

        resetFiles({ commit }) {
            commit('setProdukLampiran', []);
        }
    }
}