export default {
    state: {
        menuMemo: 'masuk',
    },
    mutations: {
        setMenuMemo(state, payload) {
            state.menuMemo = payload
        },
    },
    actions: {
        setMenuMemo({ commit }, payload) {
            commit('setMenuMemo', payload)
        }
    }
}