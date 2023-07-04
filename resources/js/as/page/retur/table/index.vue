<script>
import mixTable from '../../../mix/table'
import mix from '../../../mix/globalmix'
import modal from '../modal'
export default {
    props: ['dataTable'],
    components: {
      modal
    },
    mixins: [mixTable, mix],
    data() {
        return {
            search: '',
            id: '',
            modalDialog: false
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
      showDetail(id){
        this.id = id
        this.modalDialog = true
        this.$nextTick(() => {
          $('.modalRetur').modal('show')
        })
      },
      closeModal() {
        this.id = ''
        this.modalDialog = false
      },
      selesaikan(id) {
        this.$swal.fire({
          title: 'Apakah anda yakin?',
          text: "Anda akan menyelesaikan retur ini!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
        }).then((result) => {
          if (result.isConfirmed) {
            this.$swal.fire('Berhasil!', 'Retur berhasil diselesaikan.', 'success')
          }
        })
      }
    }
}
</script>
<template>
    <div class="p-2">
      <modal :id="id" v-if="modalDialog" @closeModal="closeModal"></modal>
      <div class="d-flex bd-highlight">
        <div class="p-2 bd-highlight">
          <input type="text" class="form-control" v-model="search" placeholder="Cari">
        </div>
        <div class="ml-auto p-2 bd-highlight"  v-if="$store.state.menuMemo != 'riwayat'">
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
            <th scope="col" v-if="$store.state.menuMemo != 'riwayat'">Status</th>
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
            <td v-if="$store.state.menuMemo != 'riwayat'"><span class="red-text badge">{{ data.status }}</span></td>
            <td>
              <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" type="button" id="showmodal" @click="showDetail(data.id)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                    <button class="dropdown-item" v-if="$store.state.menuMemo != 'riwayat'" type="button" @click="selesaikan(data.id)">
                        <i class="fas fa-check"></i>
                        Selesaikan Retur
                    </button>
                </div>
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