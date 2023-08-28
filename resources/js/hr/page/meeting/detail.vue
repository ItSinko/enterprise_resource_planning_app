<script>
import Header from '../../components/header.vue';
export default {
    props: ['meeting'],
    components: {
        Header,
    },
    data() {
        return {
            title: 'Detail Meeting',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Meeting',
                    link: '/hr/meeting'
                },
                {
                    name: 'Detail Meeting',
                    link: '/hr/meeting/detail'
                },
            ],
        }
    },
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs"></Header>
        <div class="form-group row">
            <label for="" class="col-md-3">Judul Meeting</label>
            <div class="col-md-9">
                <input type="text" class="form-control" v-model="meeting.judul" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-3">Deskripsi / Agenda Meeting</label>
            <div class="col-md-9">
                <textarea class="form-control" v-model="meeting.deskripsi" disabled></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-3">Tanggal Meeting</label>
            <div class="col-md-9">
                <input type="date" class="form-control" v-model="meeting.tanggal" disabled>
            </div>
        </div>
    </div>
</template>