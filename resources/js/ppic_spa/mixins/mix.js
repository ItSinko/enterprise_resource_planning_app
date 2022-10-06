export default {
    methods: {
        status(status){
            switch (status) {
                case 'batal':
                    return `<span class="tag is-danger is-light">${status}</span>`
                case 'penjualan':
                    return `<span class="tag is-danger is-light">${status}</span>`
                default:
                    return `
                    <progress class="progress is-success" value="${status}" max="100">${status}%</progress>
                    <span><b>${status}%</b> Selesai</span>
                    `
            }
        },

        checkdata(data){
            if(data == null){
                return `<span class="tag is-danger is-light">Belum ada</span>`
            }else{
                return data
            }
        },
        async detail(id, jenis, status){
            this.$router.push({ name: 'PenjualanDetail', params: { id, jenis, status } });
        },
        akn(akn, status){
            switch (status) {
                case 'batal':
                    return `${akn}<br> <span class="tag is-danger is-light">${status}</span>`
                case 'negosiasi':
                    return `${akn}<br> <span class="tag is-warning is-light">${status}</span>`
                case 'draft':
                    return `${akn}<br> <span class="tag is-info is-light">${status}</span>`
                case 'sepakat':
                    return `${akn}<br> <span class="tag is-success is-light">${status}</span>`
                default:
                    break;
            }
        },
        checkdata(data){
            if(data == null){
                return '-'
            }else{
                return data
            }
        },
    },
}