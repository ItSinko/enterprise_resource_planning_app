<script>
import itemSelected from "./itemSelected.vue";
import HeaderDetail from "./headerDetail.vue";
export default {
    components: {
        itemSelected,
        HeaderDetail,
    },
    props: ["meeting"],
    data() {
        return {
            itemMeetingSelected: [],
            selectedData: 0,
        };
    },
    methods: {
        selectItem(item, idx) {
            this.selectedData = idx;
            this.itemMeetingSelected = JSON.parse(JSON.stringify(item));
        },
    },
    created() {
        this.itemMeetingSelected = this.meeting[0];
    },
    computed: {
        lengthMeeting() {
            return this.meeting.length;
        },
    },
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li
                    class="nav-item"
                    role="presentation"
                    v-for="(item, idx) in meeting"
                    :key="idx"
                >
                    <a
                        class="nav-link"
                        :id="'pills-' + idx + '-tab'"
                        :class="{ active: idx === 0 }"
                        data-toggle="pill"
                        :data-target="'#pills-' + idx"
                        type="button"
                        role="tab"
                        :aria-controls="'pills-' + idx"
                        @click="selectItem(item, idx)"
                    >
                        Rencana Meeting {{ idx + 1 }}
                    </a>
                </li>
            </ul>
            <HeaderDetail
                :meeting="itemMeetingSelected"
                :lengthMeet="lengthMeeting"
                :selectedIndex="selectedData"
            />
            <itemSelected :meeting="itemMeetingSelected.peserta" />
        </div>
    </div>
</template>
