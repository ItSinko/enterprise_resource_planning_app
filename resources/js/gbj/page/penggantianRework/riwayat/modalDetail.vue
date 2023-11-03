<script>
import DataTable from '../../../components/DataTable.vue';
export default {
  props: ['headers'],
  components: {
    DataTable,
  },
  data() {
    return {
      penggantian: [
        {
          noseri: '1234567890',
          nama: 'Nama Produk',
          varian: 'Varian',
          layout: 'Layout',
        }
      ],
      headersTable: [
        {
          text: 'No Seri',
          value: 'noseri',
        },
        {
          text: 'Nama Produk',
          value: 'nama',
        },
        {
          text: 'Varian',
          value: 'varian',
        },
        {
          text: 'Layout',
          value: 'layout',
        }
      ],
      search: '',
    }
  },
  methods: {
    closeModal() {
      $('.modalDetail').modal('hide');
      this.$nextTick(() => {
        this.$emit('closeModal');
      });
    },
  },
  computed: {
    dummyPenggantian() {
      // kalikan 20 produk
      return [...Array(20).keys()].map(item => {
        return {
          noseri: '1234567890',
          nama: 'Nama Produk',
          varian: 'Varian',
          layout: 'Layout',
        }
      })
    }
  }
}
</script>
<template>
  <div class="modal fade modalDetail" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Penggantian No Seri</h5>
          <button type="button" class="close" @click="closeModal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row row-cols-2">
            <div class="col-6">
              <label for="">No Urut</label>
              <div class="card nomor-so">
                <div class="card-body">
                  <span id="so">{{
                    headers.no_urut
                  }}</span>
                </div>
              </div>
            </div>

            <div class="col-6">
              <label for="">Tanggal Penerimaan</label>
              <div class="card nomor-akn">
                <div class="card-body">
                  <span id="akn">{{
                    dateTimeFormat(headers.tgl_tf)
                  }}</span>
                </div>
              </div>
            </div>

            <div class="col-6">
              <label for="">Nama Produk</label>
              <div class="card nomor-po">
                <div class="card-body">
                  <span id="po">{{ headers.nama }}</span>
                </div>
              </div>
            </div>

            <div class="col-6">
              <label for="">Jumlah</label>
              <div class="card instansi">
                <div class="card-body">
                  <span id="instansi">{{ headers.jumlah }}</span>
                </div>
              </div>
            </div>

            <div class="col-12">
              <label for="">Keterangan</label>
              <div class="card">
                <div class="card-body">
                  <p class="card-text">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est voluptatum repellat
                    totam. Laborum fugit vel delectus. Voluptates delectus fugiat ex qui voluptatem, ea
                    reprehenderit culpa iure, atque, minima neque ab!
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
              <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
          </div>
          <DataTable :headers="headersTable" :items="dummyPenggantian" :search="search" />
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.scrollable {
  height: 200px;
  overflow-y: auto;
}

.nomor-so {
  background-color: #717fe1;
  color: #fff;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  font-size: 18px;
}

.nomor-akn {
  background-color: #df7458;
  color: #fff;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  font-size: 18px;
}

.nomor-po {
  background-color: #85d296;
  color: #fff;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  font-size: 18px;
}

.instansi {
  background-color: #36425e;
  color: #fff;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  font-size: 18px;
}
</style>