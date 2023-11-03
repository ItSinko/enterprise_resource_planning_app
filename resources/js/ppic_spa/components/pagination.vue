<script>
export default {
    props: {
        filteredDalamProses: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            currentPage: 1,
            perPage: 10,
            loading: false,
        };
    },
    methods: {
        nextPage() {
            if (this.currentPage < this.pages[this.pages.length - 1]) {
                this.currentPage++;
            }
        },
        previousPage() {
            if (this.currentPage != 1) {
                this.currentPage--;
            }
        },
        nowPage(page) {
            if (page != "...") {
                this.currentPage = page;
            }
        },
        disableClickPageThreeDots(page) {
            return page === "...";
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
            let totalPages = Math.ceil(
                this.filteredDalamProses.length / this.perPage
            );

            let pages = [];

            const totalPageNumber = this.currentPage + 4;

            for (let i = 1; i <= totalPages; i++) {
                if (i <= totalPageNumber && pages.length < 5) {
                    pages.push(i);
                } else {
                    pages.push("...");
                    pages.push(totalPages);
                    break;
                }
            }
            if (this.currentPage > 5 && this.currentPage < totalPages) {
                pages = [
                    1,
                    "...",
                    this.currentPage - 1,
                    this.currentPage,
                    this.currentPage + 1,
                    "...",
                    totalPages,
                ];
            } else if (this.currentPage > 5 && this.currentPage == totalPages) {
                pages = [1, "...", this.currentPage - 1, this.currentPage];
            }

            return pages;
        },
    },
    mounted() {
        this.$emit("updateFilteredDalamProses", this.renderPaginate);
    },
    watch: {
        renderPaginate() {
            this.$emit("updateFilteredDalamProses", this.renderPaginate);
        },
        filteredDalamProses() {
            this.currentPage = 1;
        },
    },
};
</script>
<template>
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <a class="pagination-previous" :disabled="currentPage == 1" @click="previousPage">Sebelumnya</a>
        <a class="pagination-next" :disabled="currentPage == pages[pages.length - 1]" @click="nextPage">Selanjutnya</a>
        <ul class="pagination-list">
            <li v-for="page in pages" :key="page">
                <a class="pagination-link" :class="{ 'is-current': page === currentPage }" @click="nowPage(page)"
                    :disabled="disableClickPageThreeDots(page)">
                    {{ page }}
                </a>
            </li>
        </ul>
    </nav>
</template>