<script>
import Header from '../../components/header';
import Masuk from './masuk';
import Keluar from './keluar';
export default {
    components: {
        Header,
        Masuk,
        Keluar
    },
    data() {
        return {
            title: 'Memo Retur',
                breadcumbs: [
                    {
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Memo Retur',
                        link: '/as/retur'
                    }
                ]      
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" 
                aria-selected="true"
                @click="$store.dispatch('setMenuMemo', 'masuk')"
                >Masuk</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" 
                aria-selected="false"
                @click="$store.dispatch('setMenuMemo', 'keluar')"
                >Keluar</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <Masuk />
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <Keluar />
            </div>
        </div>
    </div>
</template>