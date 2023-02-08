<script>
import moment from 'moment';
import status from '../../../components/status.vue';
import tujuan from '../../../components/tujuan.vue';
import detail from './detail'
import Detail from '../../transfer/detail.vue';
export default {
    props: {
        dalamProses: {
            type: Array,
            required: true,
        },
    },
    components: {
        status,
        tujuan,
        detail,
        Detail
    },
    data() {
        return {
            modal: false,
        }
    },
    methods: {
        moment(date){
            return moment(date).lang('id').format('LL');
        },
        detail(id){
            this.modal = true;
            this.$nextTick(() => {
                $('.modalPermintaan').modal('show');
            });
        },
        create(id){
            this.$router.push({ name: 'permintaanbahanbaku.create', params: { id: id } });
        },
        close(){
            this.modal = false;
            $('.modalPermintaan').modal('hide');
        },
    },
}
</script>
<template>
    <div>
        <detail v-if="modal" @close="close"/>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Referensi</th>
                    <th>No Permintaan</th>
                    <th>Tujuan</th>
                    <th>Tgl Permintaan</th>
                    <th>Tgl Dibutuhkan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dalamProses" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.no_referensi }}</td>
                    <td>{{ item.no_permintaan }}</td>
                    <td><tujuan :tujuan="item.tujuan" /></td>
                    <td>{{ moment(item.tgl_permintaan) }}</td>
                    <td>{{ moment(item.tgl_dibutuhkan) }}</td>
                    <td>
                        <status :status="item.status" :persentase="item.persentase" />
                    </td>
                    <td>
                                                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                            aria-expanded="true">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start"
                            style="
                    position: absolute;
                    transform: translate3d(-117px, 30px, 0px);
                    top: 0px;
                    left: 0px;
                    will-change: transform;
                  ">
                            <button 
                            @click="detail(item.id)"
                            class="dropdown-item" 
                            type="button">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>

                            <button
                            @click="create(item.id)"
                             class="dropdown-item" 
                             type="button">
                                <i class="fas fa-pencil-alt"></i>
                                Buat Penyerahan
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>