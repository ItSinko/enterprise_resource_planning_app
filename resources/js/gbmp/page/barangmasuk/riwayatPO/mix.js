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
            if(this.currentPage < this.pages[this.pages.length - 1]){
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
    computed: {
        renderPaginate() {
            return this.filteredDalamProses.slice(
              this.perPage * (this.currentPage - 1),
              this.perPage * this.currentPage
            );
          },
        pages() {
            let totalPages = Math.ceil(this.filteredDalamProses.length / this.perPage)

            let pages = []

            const totalPageNumber = this.currentPage + 4
            
            for (let i = 1; i <= totalPages; i++) {
                if(i <= totalPageNumber && pages.length < 5){
                    pages.push(i)
                }else{
                    pages.push('...')
                    pages.push(totalPages)
                    break
                }
            }
            if(this.currentPage > 5 && this.currentPage < totalPages){
                pages = [1, '...', this.currentPage - 1, this.currentPage, this.currentPage + 1, '...', totalPages]
            }else if(this.currentPage > 5 && this.currentPage == totalPages){
                pages = [1, '...', this.currentPage - 1, this.currentPage]
            }

            return pages
        }
    }
}