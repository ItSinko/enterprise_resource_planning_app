export default {
    data(){
        return {
            currentPage: 1,
            perPage: 10,
            loading: false,
        }
    },
    methods: {
        nextPage(){
            if(this.currentPage < this.paginateProducts){
                this.currentPage++
            }
        },
        previousPage(){
            if(this.currentPage != 1){
                this.currentPage--
            }
        },
        nowPage(page){
            this.currentPage = page
        },
    },
}