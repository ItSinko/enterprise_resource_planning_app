/**
 * This provides functions that is used globally in vue component
 * @namespace Mixins
 */

/**
 * This function convert status number to string
 * @memberof Mixins
 * @param {string} status - status number get from api
 * @returns {string} status string ['penyusunan', 'pelaksanaan', 'selesai']
 */
function change_status(status) {
    if (status == 6) return 'penyusunan';
    else if (status == 7) return 'pelaksanaan';
    else if (status == 8) return 'selesai';
}

/**
 * This function convert state number to string
 * @memberof Mixins
 * @param {string} status - state number get from api
 * @returns {string} state string ['perencanaan', 'persetujuan', 'perubahan']
 */
function change_state(state) {
    if (state == 17) return 'perencanaan';
    else if (state == 18) return 'persetujuan';
    else if (state == 19) return 'perubahan'
}

/**
 * this function is used to mapping jadwal array getted from api 
 * (example: '/api/ppic/data/perakitan') to array of object that 
 * can be used by other component like calnedar, table, and chart
 * @memberof Mixins
 * @param {Array} jadwal - array get from api
 * @returns {Array} schedule that had been mapping
 */
function convertJadwal (jadwal) {
    return jadwal.length == 0
        ? []
        : jadwal.map((item) => ({
            id: item.id,
            title: `${item.produk.produk.nama} ${item.produk.nama}`,
            start: item.tanggal_mulai,
            end: item.tanggal_selesai,
            backgroundColor: item.warna,
            borderColor: item.warna,
            // no_bppb: item.no_bppb,
            produk_id: item.produk_id,
            jumlah: item.jumlah,
            progres: item.noseri_count
        }));
}


export default {
    change_status,
    change_state, 
    convertJadwal
}