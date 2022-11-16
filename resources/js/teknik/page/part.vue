<script>
import Header from "../components/header.vue";
import UploadImages from "../components/upload-images.vue";
import UploadFiles from "../components/upload-files.vue";
import axios from "axios";
import mix from "./mix";
export default {
  mixins: [mix],
  components: {
    Header,
    UploadImages,
    UploadFiles,
  },
  data() {
    return {
      searchpart: "",
      parts: [],
      modalDetail: false,
      // Modal Detail Part
      tabsDetail: "detail",
      headersDetail: null,
      partDetail: null,
      detailProdukPart: [],

      // Modal Tambah & Edit Part
      jenisPart: [],
      bahanPart: [],
      satuanPart: [],
      formsTitle: "",
      formUmum: {
        jenis: null,
        kode: null,
        nama: null,
        image: null,
      },
      formSpecs: {
        panjang: null,
        lebar: null,
        tinggi: null,
        bahan: [],
        versi: null,
        satuan: null,
        fungsi: null,
        deskripsi: null,
      },
      breadcumbs: [
        {
          name: "Beranda",
          link: "#",
        },
        {
          name: "Daftar Part",
          link: "/teknik/part",
        },
      ],

      // BOM
      headerBOM: {
        namaProduk: "FOX",
        namaBOM: "BOM-001",
        kodeBOM: "BOM-001",
        tahunBuat: "2021",
        status: "Aktif",
      },
      loadingImages: false,
    };
  },
  created() {
    this.init();
  },
  updated() {
    // this.tableParts()
  },
  methods: {
    async init() {
      this.loading = true;
      await axios.get("/api/part/data").then((res) => {
        this.parts = res.data.data;
        this.loading = false;
      });

      await axios.get("/api/jenis_part/selectdata").then((res) => {
        this.jenisPart = res.data.data;
      });

      await axios.get("/api/jenis_bahan/selectdata").then((res) => {
        this.bahanPart = res.data.data;
      });

      await axios.get("/api/jenis_satuan/selectdata").then((res) => {
        this.satuanPart = res.data.data;
      });
    },
    tableParts() {
      $(".partTable").DataTable({
        destroy: true,
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: false,
        info: true,
        autoWidth: false,
        responsive: true,
      });
    },
    async detailPart(id) {
      this.modalDetail = false;
      await axios.get("/api/part/detail/" + id).then((res) => {
        this.headersDetail = res.data.data[0].header;
        this.partDetail = res.data.data[0].detail;
        this.detailProdukPart = res.data.data["bom"];
        this.modalDetail = true;
      });
      setTimeout(() => {
        let table = $(".tableDetailPart").DataTable({
          destroy: true,
          paging: true,
          lengthChange: false,
          searching: true,
          ordering: false,
          info: true,
          autoWidth: false,
          responsive: true,
        });
        $(".modalDetailPart").modal("show");
      }, 500);
    },
    textEllipsis(text, length) {
      if (text.length > length) {
        return text.substring(0, length) + "...";
      } else {
        return text;
      }
    },
    numberFormat(number) {
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    isNumber(evt) {
      evt = evt ? evt : window.event;
      var charCode = evt.which ? evt.which : evt.keyCode;
      if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        charCode !== 46
      ) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    addPart() {
      this.formsTitle = "Tambah Part";
      this.loadingImages = true;
      this.formUmum = {
        jenis: null,
        kode: null,
        nama: null,
        image: null,
      };
      this.formSpecs = {
        panjang: null,
        lebar: null,
        tinggi: null,
        bahan: [],
        versi: null,
        fungsi: null,
        deskripsi: null,
      };
      this.loadingImages = false;
      setTimeout(() => {
        $(".modalAddEdit").modal("show");
      }, 100);
    },
    handleImages(images) {
      this.formUmum.image = images[0];
      console.log("images", images);
    },
    async editPart(id) {
      this.formUmum = {
        jenis: null,
        kode: null,
        nama: null,
        image: null,
      };
      this.formSpecs = {
        panjang: null,
        lebar: null,
        tinggi: null,
        bahan: [],
        versi: null,
        fungsi: null,
        deskripsi: null,
      };
      this.formsTitle = "Edit Part";
      await axios.get(`/api/part/edit/${id}`).then((res) => {
        // this.formUmum = JSON.parse(res.data.data[0].formUmum)
        // this.formSpecs = JSON.parse(res.data.data[0].formSpecs)
        this.formUmum = res.data.data[0].formUmum;
        this.formSpecs = res.data.data[0].formSpecs;
        this.formUmum.id = id;

        if (this.formUmum.image) {
          let windowLocation = window.location.href;
          let windowLocationSplit = windowLocation.split("/");
          let url = `${windowLocationSplit[0]}//${windowLocationSplit[2]}/storage/sparepart/${res.data.data[0].formUmum.image}`;
          this.formUmum.imageEdit = url;
        }
      });
      setTimeout(() => {
        $(".modalAddEdit").modal("show");
      }, 100);
    },
    deletePart(id) {
      this.$swal({
        title: "Hapus Part",
        text: "Apakah anda yakin ingin menghapus part ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post(`/api/part/delete/${id}`).then((res) => {
            if (res.data.status === "berhasil") {
              this.$swal("Berhasil", "Part berhasil dihapus", "success");
              this.init();
            } else {
              this.$swal("Gagal", "Part gagal dihapus", "error");
            }
          });
        }
      });
    },
    deleteImage() {
      this.loadingImages = true;
      delete this.formUmum.imageEdit;
      this.formUmum.image = null;
      this.loadingImages = false;
    },
    simpan() {
      const formNotValid =
        this.formUmum.jenis != null &&
        this.formUmum.kode != null &&
        this.formUmum.nama != null &&
        this.formSpecs.bahan.length > 0 &&
        this.formSpecs.satuan != null;

      console.log("formNotValid", formNotValid);
      if (!formNotValid) {
        this.$swal({
          icon: "error",
          title: "Oops...",
          text: "Form tidak boleh ada yang kosong!",
        });
      } else {
        let data = new FormData();
        data.append("image", this.formUmum.image);
        data.append("jenis", this.formUmum.jenis.value);
        data.append("kode", this.formUmum.kode);
        data.append("nama", this.formUmum.nama);
        data.append("panjang", this.formSpecs.panjang);
        data.append("lebar", this.formSpecs.lebar);
        data.append("tinggi", this.formSpecs.tinggi);
        for (let i = 0; i < this.formSpecs.bahan.length; i++) {
          data.append("bahan[]", this.formSpecs.bahan[i].value);
        }
        data.append("versi", this.formSpecs.versi);
        data.append("satuan", this.formSpecs.satuan.value);
        data.append("fungsi", this.formSpecs.fungsi);
        data.append("deskripsi", this.formSpecs.deskripsi);
        this.$swal({
          title: "Simpan Part",
          text: "Apakah anda yakin ingin menyimpan part ini?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya",
          cancelButtonText: "Batal",
        }).then((result) => {
          if (this.formsTitle == "Tambah Part") {
            axios
              .post("/api/part/store", data, {
                headers: {
                  "Content-Type": "multipart/form-data",
                },
              })
              .then((res) => {
                console.log(res.data);
                this.$swal("Berhasil!", "Data berhasil disimpan.", "success");
                this.init();
                window.location.reload();
                $(".modalAddEdit").modal("hide");
              });
          } else {
            let id = this.formUmum.id;
            axios
              .post(`/api/part/update/${id}`, data, {
                headers: {
                  "Content-Type": "multipart/form-data",
                },
              })
              .then((res) => {
                console.log(res.data);
                this.init();
                window.location.reload();
                $(".modalAddEdit").modal("hide");
              });
          }
        });
      }
    },
    detailBOM() {
      $(".modalDetailBOM").modal("show");
      $(".tableDetailBOM").DataTable({
        destroy: true,
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: false,
        info: true,
        autoWidth: false,
        responsive: true,
      });
    },
  },
  computed: {
    partsFiltered() {
      const dataIsNotNull = (data) =>
        data !== null && data !== undefined && data !== "" ? data : "-";
      return this.parts.filter((part) => {
        const search = this.searchpart.toLowerCase();
        const kode = dataIsNotNull(part.kode).toLowerCase();
        const nama = dataIsNotNull(part.nama).toLowerCase();
        const jenis = dataIsNotNull(part.jenis).toLowerCase();
        const deskripsi = dataIsNotNull(part.deskripsi).toLowerCase();
        const satuan = dataIsNotNull(part.satuan).toLowerCase();
        return (
          kode.includes(search) ||
          nama.includes(search) ||
          jenis.includes(search) ||
          deskripsi.includes(search) ||
          satuan.includes(search)
        );
      });
    },
    renderPaginate() {
      return this.partsFiltered.slice(
        this.perPage * (this.currentPage - 1),
        this.perPage * this.currentPage
      );
    },
  },
};
</script>
<template>
  <div>
    <Header :title="'Daftar Part'" :breadcumbs="breadcumbs"></Header>
    <div class="spinner-border" role="status" v-if="loading">
      <span class="sr-only">Loading...</span>
    </div>
    <div class="card" v-else>
      <div class="card-body">
        <div class="d-flex bd-highlight buttonTambah">
          <div class="p-2 flex-grow-1 bd-highlight">
            <button @click="addPart" class="btn btn-primary">
              <i class="fas fa-plus"></i> Tambah Part
            </button>
          </div>
          <div class="p-2 bd-highlight">
            <div class="input-group input-group-sm">
              <input
                type="text"
                class="form-control"
                placeholder="Cari Part"
                v-model="searchpart"
              />
            </div>
          </div>
        </div>
        <table class="table partTable">
          <thead class="thead-light">
            <tr>
              <th></th>
              <th>Kode Part</th>
              <th>Nama Part</th>
              <th>Jenis</th>
              <th>Deskripsi</th>
              <th>Jumlah</th>
              <th>Satuan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody v-if="renderPaginate.length > 0">
            <tr v-for="part in renderPaginate" :key="part.id">
              <td>
                <img
                  :src="`/storage/sparepart/${part.gambar}`"
                  alt=""
                  width="50px"
                />
              </td>
              <td>{{ part.kode }}</td>
              <td>{{ part.nama }}</td>
              <td>
                <div class="badge badge-info">{{ part.jenis }}</div>
              </td>
              <td>{{ textEllipsis(part.deskripsi, 10) }}</td>
              <td>{{ numberFormat(part.jumlah) }}</td>
              <td>{{ part.satuan }}</td>
              <td>
                <div
                  class="dropdown-toggle"
                  data-toggle="dropdown"
                  id="dropdownMenuButton"
                  aria-haspopup="true"
                  aria-expanded="true"
                >
                  <i class="fas fa-ellipsis-v"></i>
                </div>
                <div
                  class="dropdown-menu"
                  aria-labelledby="dropdownMenuButton"
                  x-placement="bottom-start"
                  style="
                    position: absolute;
                    transform: translate3d(-117px, 30px, 0px);
                    top: 0px;
                    left: 0px;
                    will-change: transform;
                  "
                >
                  <button
                    class="dropdown-item"
                    type="button"
                    @click="detailPart(part.id)"
                  >
                    <i class="fas fa-eye"></i>
                    Detail
                  </button>

                  <button
                    @click="editPart(part.id)"
                    class="dropdown-item"
                    type="button"
                  >
                    <i class="fas fa-pencil-alt"></i>
                    Edit
                  </button>

                  <button
                    @click="deletePart(part.id)"
                    class="dropdown-item"
                    type="button"
                  >
                    <i class="far fa-trash-alt"></i>
                    Hapus
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
      </div>
      <div class="card-footer">
        <div class="d-flex flex-row-reverse bd-highlight">
          <nav aria-label="...">
            <ul class="pagination">
              <li class="page-item">
                <a
                  class="page-link"
                  :disabled="currentPage == 1"
                  @click="previousPage"
                  >Previous</a
                >
              </li>
              <li
                class="page-item"
                :class="paginate == currentPage ? 'active' : ''"
                v-for="paginate in pages"
                :key="paginate"
              >
                <a class="page-link" @click="nowPage(paginate)">{{
                  paginate
                }}</a>
              </li>
              <li class="page-item">
                <a
                  class="page-link"
                  :disabled="currentPage == pages[pages.length - 1]"
                  @click="nextPage"
                  >Next</a
                >
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>

    <!-- Detail Part -->
    <div class="modal modalDetailPart" v-if="modalDetail">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-bold">Detail Part</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <div class="col-4">
                  <div class="text-center pb-5">
                    <img
                      :src="`/storage/sparepart/${headersDetail.gambar}`"
                      width="300"
                    />
                  </div>
                </div>
                <div class="col-8">
                  <h1 class="display-4 text-bold">{{ headersDetail.nama }}</h1>
                  <p class="text-bold">{{ headersDetail.kode }}</p>

                  <div class="mt-1">
                    <p>Jenis</p>
                    <div class="btn btn-outline-secondary mt-n2">
                      {{ headersDetail.jenis }}
                    </div>
                  </div>

                  <div class="mt-2">
                    <p>Jumlah</p>
                    <p class="text-bold mt-n2">
                      {{ headersDetail.jumlah }} pcs
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="container">
              <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item">
                  <a
                    class="nav-link active"
                    id="pills-detail-tab"
                    data-toggle="pill"
                    href="#pills-detail"
                    role="tab"
                    aria-controls="pills-detail"
                    aria-selected="true"
                    @click="tabsDetail == 'detail'"
                    >Detail</a
                  >
                </li>
                <li class="nav-item">
                  <a
                    class="nav-link"
                    id="pills-bom-tab"
                    data-toggle="pill"
                    href="#pills-bom"
                    role="tab"
                    aria-controls="pills-bom"
                    aria-selected="true"
                    @click="tabsDetail == 'bom'"
                    >BOM</a
                  >
                </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div
                  class="tab-pane fade show active"
                  id="pills-detail"
                  role="tabpanel"
                  aria-labelledby="pills-detail-tab"
                >
                  <div class="card bg-secondary mt-2">
                    <div class="card-body">
                      <h4 class="card-title">Deskripsi</h4>
                      <p class="card-text">{{ partDetail.deskripsi }}</p>
                    </div>
                  </div>

                  <div class="card bg-secondary mt-2">
                    <div class="card-body">
                      <h4 class="card-title">Spesifikasi</h4>
                      <p class="card-text">Bahan</p>
                      <p class="card-text" v-for="(bahan, idx) in partDetail.spesifikasi.bahan" :key="`bahan${idx}`">
                        <span>{{ bahan.nama }}</span>
                      </p>
                      <p class="card-text">Dimensi</p>
                      <p class="card-text">
                        {{ partDetail.spesifikasi.dimensi }}
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  class="tab-pane fade"
                  id="pills-bom"
                  role="tabpanel"
                  aria-labelledby="pills-bom-tab"
                >
                  <table class="table tableDetailPart mt-2" v-if="modalDetail">
                    <thead class="thead-light">
                      <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Versi BOM</th>
                        <th>Jumlah Kebutuhan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(detail, key) in detailProdukPart"
                        :key="`${detail.id}detail`"
                      >
                        <td>{{ key + 1 }}</td>
                        <td>{{ detail.produk }}</td>
                        <td>{{ detail.versi }}</td>
                        <td>{{ numberFormat(detail.jumlah) }}</td>
                        <td>
                          <button
                            class="btn btn-sm btn-outline-info"
                            @click="detailBOM"
                          >
                            <i class="fas fa-eye" aria-hidden="true"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Edit Part -->
    <div class="modal modalAddEdit" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ formsTitle }}</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="card">
                <div class="card-body">
                  <div class="card-title text-bold pb-2">Informasi Umum</div>
                  <div class="card-text">
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="">Jenis</label>
                          <v-select
                            :options="jenisPart"
                            v-model="formUmum.jenis"
                          ></v-select>
                        </div>

                        <div class="form-group">
                          <label for="">Kode Part</label>
                          <input
                            type="text"
                            class="form-control"
                            v-model="formUmum.kode"
                          />
                        </div>

                        <div class="form-group">
                          <label for="">Nama Part</label>
                          <textarea
                            class="form-control"
                            cols="5"
                            rows="5"
                            v-model="formUmum.nama"
                          ></textarea>
                        </div>
                      </div>

                      <div class="col" v-if="!loadingImages">
                        <div
                          class="card"
                          style="width: 18rem"
                          v-if="
                            formUmum.imageEdit != null &&
                            formUmum.imageEdit != undefined
                          "
                        >
                          <img :src="formUmum.imageEdit" />
                          <div class="card-img-overlay">
                            <button
                              class="btn btn-sm btn-danger"
                              @click="deleteImage"
                            >
                              <i class="fas fa-trash" aria-hidden="true"></i>
                            </button>
                          </div>
                        </div>
                        <UploadImages
                          @changed="handleImages"
                          :max="1"
                          :images="formUmum.image"
                          v-else
                          maxError="Maksimal 1 gambar"
                          uploadMsg="Silahkan Upload Gambar Part Disini"
                          clearAll="Hapus semua gambar"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title text-bold pb-2">Informasi Spesifik</h4>
                  <div class="card-text">
                    <p>Spesifikasi</p>
                    <div class="row">
                      <div class="col-6">
                        <div class="row">
                          <div class="col">
                            <label for="">Panjang</label>
                            <input
                              type="text"
                              v-model="formSpecs.panjang"
                              @keypress="isNumber($event)"
                              class="form-control"
                            />
                          </div>
                          <div class="col">
                            <label for="">Lebar</label>
                            <input
                              type="text"
                              v-model="formSpecs.lebar"
                              @keypress="isNumber($event)"
                              class="form-control"
                            />
                          </div>
                          <div class="col">
                            <label for="">Tinggi</label>
                            <input
                              type="text"
                              v-model="formSpecs.tinggi"
                              @keypress="isNumber($event)"
                              class="form-control"
                            />
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="">Bahan</label>
                          <v-select multiple
                            v-model="formSpecs.bahan"
                            :options="bahanPart"
                          ></v-select>
                        </div>
                        <div class="form-group">
                          <label for="">Versi</label>
                          <input
                            type="text"
                            v-model="formSpecs.versi"
                            class="form-control"
                          />
                        </div>
                        <div class="form-group">
                          <label for="">Satuan</label>
                          <v-select
                            v-model="formSpecs.satuan"
                            :options="satuanPart"
                          ></v-select>
                        </div>
                      </div>
                      <div class="col-1"></div>
                      <div class="col-5">
                        <div class="form-group">
                          <label for="">Fungsi</label>
                          <textarea
                            v-model="formSpecs.fungsi"
                            cols="5"
                            rows="3"
                            class="form-control"
                          ></textarea>
                        </div>
                        <div class="form-group">
                          <label for="">Deskripsi</label>
                          <textarea
                            v-model="formSpecs.deskripsi"
                            cols="5"
                            rows="3"
                            class="form-control"
                          ></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Batal
            </button>
            <button type="button" class="btn btn-primary" @click="simpan">
              Simpan
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail BOM -->
    <div
      class="modal modalDetailBOM"
      id="modelId"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-bold">Detail BOM</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="card bg-card-danger">
                      <div class="card-body">
                        <div class="card-title">Nama Produk</div>
                        <div class="card-text text-bold">
                          {{ headerBOM.namaProduk }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="card bg-card-success">
                      <div class="card-body">
                        <div class="card-title">Nama BOM</div>
                        <div class="card-text text-bold">
                          {{ headerBOM.namaBOM }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="card bg-card-secondary">
                      <div class="card-body">
                        <div class="card-title">Kode BOM</div>
                        <div class="card-text text-bold">
                          {{ headerBOM.kodeBOM }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card bg-card-danger">
                      <div class="card-body">
                        <div class="card-title">Tahun Pembuatan</div>
                        <div class="card-text text-bold">
                          {{ headerBOM.tahunBuat }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card bg-card-secondary">
                      <div class="card-body">
                        <div class="card-title">Status</div>
                        <div class="card-text">
                          <span class="badge badge-danger">{{
                            headerBOM.status
                          }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-body">
                <div class="card-title text-bold">Part Bill Of Material</div>
                <div class="card-text">
                  <table class="table">
                    <thead class="thead-light">
                      <tr>
                        <th>No</th>
                        <th>Kode Part</th>
                        <th>Nama Part</th>
                        <th>Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.bg-card-danger {
  background-color: #ebdfdf;
  color: #9d6569;
}
.bg-card-success {
  background-color: #e0e2df;
  color: #697569;
}
.bg-card-secondary {
  background-color: #dce2e5;
  color: #536c7c;
}
</style>
