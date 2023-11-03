<!-- Modal -->
<div class="modal fade modalDetailNoSeri" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail No Seri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm">
                        <label for="">Nomor Seri</label>
                        <div class="card nomor-so">
                            <div class="card-body">
                                <span id="nomor-seri-reworks">

                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm">
                        <label for="">Tanggal Dibuat</label>
                        <div class="card nomor-akn">
                            <div class="card-body">
                                <span id="tgl-dibuat-reworks">

                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm">
                        <label for="">Packer</label>
                        <div class="card nomor-po">
                            <div class="card-body">
                                <span id="packer-reworks">

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table tableprodukreworks">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Nomor Seri</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<style>
    .nomor-so {
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-akn {
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-po {
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
</style>
