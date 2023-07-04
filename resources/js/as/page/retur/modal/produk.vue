<script>
import mix from '../../../mix/table'
export default {
    mixins: [mix],
    props: ['dataTable'],
    data() {
        return {
            search: '',
        }
    },
}
</script>
<template>
    <div>
        <!-- flex right -->
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari">
            </div>
        </div>
        <table class="table">
            <thead class="bg-secondary">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>No Seri</th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(data, index) in renderPaginate">
                    <td>
                        {{ index + 1 }}
                    </td>
                    <td>
                        {{ data.nama }}
                    </td>
                    <td>
                        <span class="green-text badge">
                            {{ data.jenis }}
                        </span>
                    </td>
                    <td>
                        {{ data.jumlah }}
                    </td>
                    <td>
                        {{ data.no_seri[0] }}
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="5" class="text-center">Data tidak ditemukan</td>
                </tr>
            </tbody>
        </table>    
        <div class="d-flex flex-row-reverse bd-highlight">
            <nav aria-label="...">
              <ul class="pagination">
                <li class="page-item">
                  <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                </li>
                <li class="page-item" v-for="paginate in pages" 
                :key="paginate" 
                :class="paginate == currentPage ? 'active' : ''"
                >
                  <a class="page-link" :class="['page-link', { 'disabled': paginate === '...' }]"
                    @click="paginate !== '...' ? nowPage(paginate) : null">{{paginate}}</a>
                </li>
                <li class="page-item">
                  <a class="page-link" :disabled="currentPage == pages[pages.length - 1]" @click="nextPage">Next</a>
                </li>
              </ul>
</nav>
        </div>
    </div>
</template>