<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="modal fade modalDistributor" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nomor Seri Distributor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="indexSeriDistributor">
                <input type="hidden" class="jumlahSeriDistributor">
                <textarea class="form-control nomorSeriDistributor"
                onkeyup="textToUppercase(this)"
                ></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary simpanSeriDistributor">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.simpanSeriDistributor', function () {
        let index = $('.indexSeriDistributor').val();
        let jumlah = $('.jumlahSeriDistributor').val();
        let nomor_seri = $('.nomorSeriDistributor').val().split(/[\n, \t]/);
        nomor_seri = nomor_seri.map(function (item) {
            return item.trim();
        });
        nomor_seri = nomor_seri.filter(function (el) {
            return el != '';
        });
        nomor_seri = [...new Set(nomor_seri)];

        if(jumlah == ''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Jumlah barang tidak boleh kosong!',
            })
            return
        }

        if(jumlah != nomor_seri.length){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Jumlah nomor seri tidak sesuai!',
            })
            return
        }
        $('.noSeriDistributor').eq(index).val(nomor_seri);
        // close modal
        $('.modalDistributor').modal('hide');
    })

    const textToUppercase = (element) => {
        $(element).val($(element).val().toUpperCase());
    }
</script>