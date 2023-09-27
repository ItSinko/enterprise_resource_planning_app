<script>
export default {
    props: {
        DataTable: {
            type: Array,
            required: true,
        },
    },
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
            if(page != '...'){
                this.currentPage = page
            }
        },
        disableClickPageThreeDots(page){
           return page === '...'
        }
    },
    computed: {
        renderPaginate() {
            return this.DataTable.slice(
              this.perPage * (this.currentPage - 1),
              this.perPage * this.currentPage
            );
          },
        pages() {
            let totalPages = Math.ceil(this.DataTable.length / this.perPage)

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
    },
    mounted(){
        this.$emit('updatePage', this.renderPaginate)
    },
    watch: {
        renderPaginate(){
            this.$emit('updatePage', this.renderPaginate)
        },
        DataTable(){
            this.currentPage = 1
        }
    },
}
</script>
<template>
    <div class="d-flex flex-row-reverse bd-highlight">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                            </li>
                            <li class="page-item" :class="paginate == currentPage ? 'active' : ''"
                                v-for="paginate in pages" :key="paginate">
                                <a class="page-link" @click="nowPage(paginate)" :disabled="disableClickPageThreeDots(paginate)" >{{paginate}}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" :disabled="currentPage == pages[pages.length - 1]"
                                    @click="nextPage">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
</template>