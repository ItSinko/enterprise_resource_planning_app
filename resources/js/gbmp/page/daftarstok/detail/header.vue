<script>
    export default {
        data() {
            return {
                formHeader: {
                    img: 'https://picsum.photos/500',
                    part: 'SPC-0001',
                    nama: 'Spare Part 1',
                    jenis_part: 'Spare Part',
                    satuan: 'PCS',
                    part_ok: 100,
                    part_not_ok: 10,
                }
            }
        },
    }

</script>
<template>
    <div>
        <div class="row">
            <div class="col-4">
                <img :src="formHeader.img" width="400" alt="" class="img-fluid">
            </div>
            <div class="col">
                <h1 class="text-bold">{{ formHeader.part }}</h1>
                <h5 class="text-muted text-bold">{{ formHeader.nama }}</h5>
                
                <div class="bg-secondary p-3 mt-5">
                    <div class="row">
                        <div class="col mb-4">
                            <i class="fas fa-box"></i>
                            <span class="mx-2">Jenis Part</span>
                            <span class="text-bold">
                                {{ formHeader.jenis_part }}
                            </span>
                        </div>
                        <div class="col mb-4">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                            <span class="mx-2">Jumlah Part OK</span>
                            <span class="text-bold">
                                {{ formHeader.part_ok }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <i class="fa fa-balance-scale" aria-hidden="true"></i>
                            <span class="mx-2">Satuan</span>
                            <span class="text-bold">
                                {{ formHeader.satuan }}
                            </span>
                        </div>
                        <div class="col mb-4">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                            <span class="mx-2">Jumlah Part Not OK</span>
                            <span class="text-bold">
                                {{ formHeader.part_not_ok }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
