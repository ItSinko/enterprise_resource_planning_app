<script>
import axios from 'axios';
import Header from "../../../components/header.vue";
import HeaderDetail from "./header.vue";
import Item from "./item.vue";
export default {
    components: {
        Header,
        HeaderDetail,
        Item,
    },
    data() {
        return {
            title: "Detail Meeting",
            breadcumbs: [
                {
                    name: "Beranda",
                    link: "#",
                },
                {
                    name: "Meeting",
                    link: "/meeting/hr",
                },
                {
                    name: "Detail Meeting",
                    link: "/meeting/hr/detail",
                },
            ],
            meeting: [],
            itemMeetingSelected: [],
            selectedData: 0,
        };
    },
    methods: {
        closeModal() {
            $(".modalDetail").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
        selectItem(item, idx) {
            this.selectedData = idx;
            this.itemMeetingSelected = JSON.parse(JSON.stringify(item));
        },
        async getDetail() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get(`/api/hr/meet/jadwal/${this.$route.params.id}`);
                this.meeting = data.riwayat;
                if (this.meeting.length == data.riwayat.length) {
                    // let dataSementara = data;
                    // delete dataSementara.riwayat;
                    // this.meeting.push(dataSementara);
                    console.log('oke');
                }
                this.itemMeetingSelected = this.meeting[0] == undefined || this.meeting[0] == null ? data : this.meeting[0];
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        }
    },
    created() {
        this.getDetail();
    },
    computed: {
        lengthMeeting() {
            return this.meeting.length;
        },
    },
};
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation" v-for="(item, idx) in meeting" :key="idx">
                <a class="nav-link" :id="'pills-' + idx + '-tab'" :class="{ active: idx === 0 }" data-toggle="pill"
                    :data-target="'#pills-' + idx" type="button" role="tab" :aria-controls="'pills-' + idx"
                    @click="selectItem(item, idx)">
                    Rencana Meeting {{ idx + 1 }}
                </a>
            </li>
        </ul>
        <HeaderDetail :meeting="itemMeetingSelected" :lengthMeet="lengthMeeting" :selectedIndex="selectedData" />
        <Item :meeting="itemMeetingSelected.peserta" />
    </div>
</template>
