import axios from 'axios'

export default {
    data() {
        return {
            jenisPembelian: [
                'Online',
                'Offline'
            ],
            aset: [],
            supplier: [],
        }
    },
    created() {
        this.getData()
    },
    methods: {
        validationLink(condition){
            switch (condition) {
                case 'Online':
                    return false            
                default:
                    return true
            }
        },
        async getData(){
            const { data } = await axios.get('/api/aset').then(res => res.data)
            this.aset = data.map(item => {
                return {
                    value: item.id,
                    label: `${item.merk} - ${item.nama}`,
                    merk: item.merk,
                    nama: item.nama,
                    no_perkiraan: item.perkiraan
                }
            })

            const { data: dataSupplier } = await axios.get('/api/supplier').then(res => res.data)
            this.supplier = dataSupplier.map(item => {
                return {
                    value: item.id,
                    label: item.nama
                }
            })
        },  
    },
}