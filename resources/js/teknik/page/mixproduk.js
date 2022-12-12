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
        filteredProducts() {
            const dataIsNotNull = (data) =>
                data !== null && data !== undefined && data !== "" ? data : "";
            return this.products.filter((row) => {
                return Object.keys(row).some((key) => {
                    return String(dataIsNotNull(row[key])).toLowerCase().includes(this.search.toLowerCase())
                })
            })

        },
        renderPaginate() {
            return this.filteredProducts.slice(this.perPage * (this.currentPage - 1), this.perPage * this.currentPage)
        },
        productsNested() {
            let nested = []
            let categories = []
            let types = []
            this.renderPaginate.forEach(product => {
                if (!categories.includes(product.kategori)) {
                    categories.push(product.kategori)
                }
                if (!types.includes(product.jenis)) {
                    types.push(product.jenis)
                }
            })
            categories.forEach(category => {
                let categoryObj = {}
                categoryObj[category] = []
                types.forEach(type => {
                    let typeObj = {}
                    typeObj[type] = []
                    this.renderPaginate.forEach(product => {
                        if (product.kategori == category && product.jenis == type) {
                            typeObj[type].push(product)
                        }
                    })
                    if (typeObj[type].length > 0) {
                        categoryObj[category].push(typeObj)
                    }
                })
                nested.push(categoryObj)
            })
            return nested
        },
        pages() {
            let totalPages =  Math.ceil(this.filteredProducts.length / this.perPage)
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