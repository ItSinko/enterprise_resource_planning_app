export default {
    state: {
        SO: [],
        POEkat: [],
        PONonEkat: [],
        DO: [],
        provinsi: [],
    },
    mutations: {
        setDataSO(state, data) {
            state.SO = data;
        },
        setDataPOEkat(state, data) {
            state.POEkat = data;
        },
        setDataPONonEkat(state, data) {
            state.PONonEkat = data;
        },
        setDataDO(state, data) {
            state.DO = data;
        },
        setProvinsi(state, data) {
            state.provinsi = data;
        },
    }
}