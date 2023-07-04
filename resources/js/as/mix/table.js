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
        filteredDatatables() {
            const dataIsNotNull = (data) =>
                data !== null && data !== undefined && data !== "" ? data : "-";
            return this.dataTable?.filter((dataTable) => {
                return Object.keys(dataTable).some((key) =>
                    dataIsNotNull(dataTable[key])
                    .toString()
                    .toLowerCase()
                    .includes(this.search.toLowerCase())
                );
            });
        },
        renderPaginate() {
            return this.filteredDatatables?.slice(
                this.perPage * (this.currentPage - 1),
                this.perPage * this.currentPage
              );
          },
        pages() {
            let totalPages = Math.ceil(this.filteredDatatables?.length / this.perPage);
            let pages = [];
            
            if (totalPages <= 5) {
              for (let i = 1; i <= totalPages; i++) {
                pages.push(i);
              }
            } else {
              if (this.currentPage <= 3) {
                for (let i = 1; i <= 5; i++) {
                  pages.push(i);
                }
                pages.push('...');
                pages.push(totalPages);
              } else if (this.currentPage >= totalPages - 2) {
                pages.push(1);
                pages.push('...');
                for (let i = totalPages - 4; i <= totalPages; i++) {
                  pages.push(i);
                }
              } else {
                pages.push(1);
                pages.push('...');
                for (let i = this.currentPage - 1; i <= this.currentPage + 1; i++) {
                  pages.push(i);
                }
                pages.push('...');
                pages.push(totalPages);
              }
            }
            
            return pages;            
        }
    },
    watch: {
        search(){
            this.currentPage = 1
        }
    }
}