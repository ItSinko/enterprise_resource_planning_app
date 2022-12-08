export default {
    data(){
        return {
            currentPage: 1,
            perPage: 10,
            loading: false,
            search: '',
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
        isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },
    },
    computed: {
        filteredBOMs() {
            const dataIsNotNull = (data) =>
                data !== null && data !== undefined && data !== "" ? data : "";
            return this.boms.filter((row) => {
                return Object.keys(row).some((key) => {
                    return String(dataIsNotNull(row[key])).toLowerCase().includes(this.search.toLowerCase())
                })
            })

        },
        renderPaginate() {
            return this.filteredBOMs.slice(this.perPage * (this.currentPage - 1), this.perPage * this.currentPage)
        },
        bomNested() {
            let nested = []
            let products = []
            this.renderPaginate.forEach((bom) => {
                if(!products.includes(bom.produk)){
                    products.push(bom.produk)
                }
            })
            products.forEach((product) => {
                let productObj = {}
                productObj[product] = []
                this.renderPaginate.forEach((bom) => {
                    if(bom.produk == product){
                        productObj[product].push(bom)
                    }
                }
                )
                nested.push(productObj)
            })
            return nested  
        },
        pages() {
            let totalPages =  Math.ceil(this.filteredBOMs.length / this.perPage)
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
        },
    }
}