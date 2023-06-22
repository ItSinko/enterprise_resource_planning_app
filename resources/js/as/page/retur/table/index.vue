<script>
import mixTable from '../../../mix/table'
import mix from '../../../mix/globalmix'
export default {
    props: ['dataTable'],
    mixins: [mixTable, mix],
    data() {
        return {
            search: '',
        }
    },
    methods: {
      checkNullOrUndefined(value) {
        if (value == null || value == undefined) {
          return '-'
        } else {
          return value
        }
      },
      tambah() {
        switch (this.$store.state.menuMemo) {
          case 'masuk':
            return this.$router.push({ name: 'retur-form-masuk' })
            break;
          default:
            return this.$router.push({ name: 'retur-form-keluar' })
            break;
        }
      },
    }
}
</script>
<template>
    <div class="p-2">
      <div class="d-flex bd-highlight">
        <div class="p-2 bd-highlight">
          <input type="text" class="form-control" v-model="search" placeholder="Cari">
        </div>
        <div class="ml-auto p-2 bd-highlight">
          <button class="btn btn-info mb-2" @click="tambah">Tambah</button> 
        </div>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">No Retur</th>
            <th scope="col">No Referensi</th>
            <th scope="col">Tanggal Retur</th>
            <th scope="col">Jenis Retur</th>
            <th scope="col">Customer</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody v-if="renderPaginate.length > 0">
          <tr v-for="(data, index) in renderPaginate">
            <th scope="row">{{ index + 1 }}</th>
            <td>{{ data.no_retur }}</td>
            <td>{{ checkNullOrUndefined(data.no_po) }}</td>
            <td>{{ moment(data.tanggal_retur) }}</td>
            <td>
              <span class="badge" :class="jenis(data.jenis)">
                {{ data.jenis }}
              </span>
            </td>
            <td>{{ data.customer }}</td>
            <td><span class="red-text badge">{{ data.status }}</span></td>
            <td>
              <button class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye"></i>
                Detail
              </button>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="8" class="text-center">Tidak ada data</td>
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