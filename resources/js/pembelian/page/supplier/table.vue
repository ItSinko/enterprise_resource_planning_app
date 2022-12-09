<script>
    export default {
        props: {
            dataTable: {
                type: Array,
                default: () => []
            }
        },
    }

</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Jenis</th>
                    <th>Kurs</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(supplier, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ supplier.kode }}</td>
                    <td>{{ supplier.nama }}</td>
                    <td>{{ supplier.jenis }}</td>
                    <td>{{ supplier.kurs }}</td>
                    <td>{{ supplier.email }}</td>
                    <td>{{ supplier.telepon }}</td>
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
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>

                            <button class="dropdown-item" type="button">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="8">Data tidak ditemukan</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
