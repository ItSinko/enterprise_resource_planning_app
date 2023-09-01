<script>
var rij;

function start() {
  rij = event.target;
}
function dragover() {
  var e = event;
  e.preventDefault();

  let children = Array.from(e.target.parentNode.parentNode.children);

  if (children.indexOf(e.target.parentNode) > children.indexOf(rij))
    e.target.parentNode.after(rij);
  else e.target.parentNode.before(rij);

  var table = document.getElementById("table");

  var row = table.rows;
  for (var j = 1; j < row.length; j++) {
    document.getElementById("table").rows[j].cells.item(0).innerHTML =
      "marker" + j;
  }
}
// this is where the draggability code ends

import moment from "moment";
import kehadiran from "../../../../components/kehadiran.vue";
import modal from "./modal.vue";
export default {
    props: ["meeting", "status"],
    components: {
        kehadiran,
        modal,
    },
    data() {
        return {
            search: "",
            showModal: false,
            formnotulen: {
                penanggungjawab: "",
                isi: "",
                kesesuaian: "",
                catatan: "",
            },
            dragged: null,
        };
    },
    methods: {
        formatDateTime(date) {
            return moment(date).lang("id").format("DD/MM/YYYY HH:mm");
        },
        addNotulen() {
            this.showModal = true;
            // reset formnotulen
            this.formnotulen = {
                penanggungjawab: "",
                isi: "",
                kesesuaian: "",
                catatan: "",
            };
            this.$nextTick(() => {
                $(".modalNotulen").modal("show");
            });
        },
        close() {
            this.showModal = false;
        },
        save() {
            this.showModal = false;
            this.$nextTick(() => {
                $(".modalNotulen").modal("hide");
            });
        },
        start() {
            console.log("start");
        },
        dragover() {
            console.log("dragover");
        },
    },
};



</script>
<template>
    <div class="card">
    <modal :formnotulen="formnotulen" @save="save" v-if="showModal" @closeModal="close"/>
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <button class="btn btn-primary" @click="addNotulen" v-if="status == 'menyusun_hasil_meeting'">
                        <i class="fa fa-plus"></i>
                        Tambah</button>
                </div>
                <div class="p-2">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <table class="table" id="table">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Penanggung Jawab</th>
                        <th rowspan="2">Uraian</th>
                        <th colspan="2">Kesesuian</th>
                        <th rowspan="2">Catatan</th>
                    </tr>
                    <tr>
                        <th>Hasil</th>
                        <th>Dicek Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, idx) in meeting"
                    @draggable="true"
                    @ondragstart="start()"
                    @ondragover="dragover()"
                    >
                        <td class="text-center">{{ idx + 1 }}</td>
                        <td class="text-center">
                            {{ item.penanggungjawab }} - {{ item.divisi }}
                        </td>
                        <td>{{ item.isi }}</td>
                        <td class="text-center">
                            <kehadiran :kehadiran="item.kesesuaian" />
                        </td>
                        <td class="text-center">
                            {{ item.penanggungjawab }},
                            {{ formatDateTime(item.created_at) }}
                        </td>
                        <td>
                            {{ item.catatan ?? "-" }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
