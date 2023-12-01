<script>
import axios from 'axios';
export default {
    props: ['jumlahMaksKirim'],
    data() {
        return {
            form: {
                provinsi: '',
                kota: '',
                jumlah: '',
            },
            provinsi: [],
            kota: [],
            loading: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalPembagian').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
                this.$emit('refresh');

            });
        },
        getProvinsi() {
            try {
                this.loading = true;
                axios.get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json').then((response) => {
                    this.provinsi = response.data.map((data) => {
                        return {
                            label: data.name,
                            value: data.id,
                        }
                    });
                });

            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },
        getKota() {
            try {
                let id = this.form.provinsi?.value;
                axios.get(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`).then((response) => {
                    this.kota = response.data.map((data) => {
                        return {
                            label: data.name,
                            value: data.id,
                        }
                    });
                });

            } catch (error) {
                console.log(error);
            }
        },
        async kirim() {
            const cekNull = Object.values(this.form).every((data) => data !== '' && data !== null);

            if (cekNull && this.form.jumlah <= this.jumlahMaksKirim) {
                const { data } = await axios.post(`/api/logistik/rw/pack_wilayah/store/${this.$route.params.id}`, this.form);
                if (data.status) {
                    this.$swal('Berhasil', data.message, 'success');
                    this.closeModal();
                } else {
                    this.$swal('Error', data.message, 'error');
                }
            } else {
                this.$swal('Error', 'Silahkan cek kembali data anda', 'error');
            }
        }
    },
    created() {
        this.getProvinsi();
    }
}
</script>
<template>
    <div class="modal fade modalPembagian" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Pembagian Wilayah Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="!loading">
                    <div class="form-group">
                        <label for="">Provinsi</label>
                        <v-select :options="provinsi" v-model="form.provinsi" @input="getKota" />
                    </div>
                    <div class="form-group">
                        <label for="">Kab. / Kota</label>
                        <v-select v-model="form.kota" :options="kota" />
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah Pengiriman</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': form.jumlah > jumlahMaksKirim }"
                            v-model="form.jumlah" placeholder="Jumlah Pengiriman" @keypress="numberOnly($event)"
                            @keyup.enter="kirim">
                        <div id="validationServer04Feedback" class="invalid-feedback">
                            Jumlah pengiriman tidak boleh lebih dari {{ jumlahMaksKirim }}
                        </div>
                    </div>
                </div>
                <div class="spinner-border" role="status" v-else>
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="kirim">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>