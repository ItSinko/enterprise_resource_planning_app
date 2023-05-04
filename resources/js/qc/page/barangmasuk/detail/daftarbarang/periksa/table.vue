<script>
    export default {
        props: {
            dataTable: {
                type: Array,
                default: () => {
                    return []
                }
            }
        }
    }

</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Pemeriksaan Barang</h4>

            <div class="card-text">
                <div class="d-flex justify-content-center">
                    <div class="form-group row">
                        <label for="" class="col-sm-2 form-label">No Laporan</label>
                        <div class="col-sm-10"><input type="text" class="form-control"></div>
                    </div>
                </div>

                <table class="table text-center">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2">Nama Produk</th>
                            <th rowspan="2">
                                <input type="checkbox" name="" id="">
                                AQL 6,5
                            </th>
                            <th colspan="2">
                                Jumlah
                            </th>
                            <th colspan="2">
                                Hasil
                            </th>
                            <th rowspan="2">
                                Permasalahan
                            </th>
                            <th rowspan="2">
                                Keterangan
                            </th>
                        </tr>
                        <tr>
                            <th>Diterima</th>
                            <th>Sample</th>
                            <th>OK</th>
                            <th>NOK</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</template>
