import Vue from 'vue'
import Vuex from 'vuex'
import mixins from '../mixins'

/**
 * This module is used for store global variables and functions to modified
 * global variables
 * @namespace Store
 */

Vue.use(Vuex)

/**
 * this function is used to change data isLoading member of state object,
 * isLoading variable mostly used as flag to show and hide loading animation
 * in all components vue
 * @memberof Store
 * @param {Object} state
 * @param {Boolean} status
 * 
 */
function setIsLoading(state, status) {
    state.isLoading = status
}

/**
 * this function is used to change data jadwal member of state object, 
 * this function also used to initialize other variables such as:
 * status, state, konfirmasi, and state_ppic based on jadwal variable
 * @memberof Store
 * @param {Object} state 
 * @param {Object} jadwal 
 */
function setJadwal(state, jadwal) {
    state.jadwal = jadwal;

    // reset state if jadwal length is 0
    if (state.jadwal.length === 0) {
        state.state = 'perencanaan';
        state.konfirmasi = 0;
        state.state_ppic = "pembuatan";
    }

    // set konfirmasi
    let flag = false;
    for (let i = 0; i < jadwal.length; i++) {
        if (!flag) {
            if (jadwal[i].konfirmasi !== 1) {
                if (jadwal[i].konfirmasi === 0) {
                    state.konfirmasi = 0
                    flag = true
                }

                if (jadwal[i].konfirmasi === 2) {
                    state.konfirmasi = 2
                    flag = true
                }
            } else {
                state.konfirmasi = 1
            }
        }
    }

    // set status and state
    if (jadwal.length > 0) {
        state.state = mixins.change_state(jadwal[0].state)
        state.status = mixins.change_status(jadwal[0].status)
    }

    // set ppic state
    if (state.konfirmasi == 0 && state.state !== "perencanaan") state.state_ppic = "menunggu"
    else if (state.state === "perencanaan") state.state_ppic = "pembuatan"
    else if (state.state === "persetujuan" && state.konfirmasi == 0) state.state_ppic = "menunggu"
    else if (state.state === "persetujuan" && state.konfirmasi == 1) state.state_ppic = "disetujui"
    else if (state.state === "persetujuan" && state.konfirmasi == 2) state.state_ppic = "revisi"
}

/**
 * This function used to set status variable
 * @memberof Store
 * @param {Object} state 
 * @param {String} status 
 */
function setStatus(state, status) {
    state.status = status;
}

/**
 * This function used to set user variable
 * @memberof Store
 * @param {Object} state 
 * @param {Object} user 
 */
function setUser(state, user) {
    state.user = user;
}


/**
 * This function used to set user variable
 * @memberof Store
 * @param {Object} state 
 * @param {Object} notif 
 */
 function setNotif(state, notif) {
    state.notif = notif;
}

const store = new Vuex.Store({
    /**
     * State is object that store global variable used in vue component
     * @memberof Store
     * @example
     * this.$store.state.user -> this code is used to retrieve user data variable
     */
    state: {
        // global
        user: {},
        isLoading: false,
        csrf_token: "",
        notif: {},
        enable_notif: true,

        // jadwal perakitan
        jadwal: [],
        status: 'penyusunan', // [penyusunan, pelaksanaan, selesai]
        state: 'perencanaan', // [perencanaan, persetujuan, perubahan]
        konfirmasi: 0, // [0 => inisial, 1 => setuju, 2 => tolak]
        state_ppic: "pembuatan", // [pembuatan, revisi, disetujui, menunggu]
    },

    /**
     * Mutations is object of functions that  used to change the value
     * of state object
     * @memberof Store
     * @example
     * this.$store.commit('setIsLoading', true) -> to set isLoading variable to true
     */
    mutations: {
        setIsLoading,
        setJadwal,
        setStatus,
        setUser,
        setNotif,
    },

})

export default store