export default {
    data() {
        return {
            jenisPembelian: [
                'Online',
                'Offline'
            ]
        }
    },
    methods: {
        validationLink(condition){
            switch (condition) {
                case 'Online':
                    return false            
                default:
                    return true
            }
        }
    },
}