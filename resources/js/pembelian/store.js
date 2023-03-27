export default {
    state: {
        loading: false,
    },
    mutations: {
        setLoading(state, data) {
            state.loading = data;
        }
    },
    actions: {
        setLoading({ commit }, data) {
            commit('setLoading', data);
        }
    }
}