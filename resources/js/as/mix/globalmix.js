import moment from 'moment'
export default {
    methods: {
        moment(date) {
            return moment(date).format('DD MMMM YYYY')
          },
          jenis(value) {
            switch (value) {
              case 'peminjaman':
                return 'purple-text'
                break;
              case 'komplain':
                return 'blue-text'
                break;
              case 'service':
                return 'orange-text'
                break;
              default:
                return 'red-text'
                break;
            }
          },
    },
}