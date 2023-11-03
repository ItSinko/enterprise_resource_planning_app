<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="modalautocheckbox" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="resetNomorSeri()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="" class="col-form-label">Nomor Seri</label>
                <textarea cols="30" rows="10" class="form-control" id="nomor_seri"></textarea>
                <div class="text-muted">
                    <small>
                        <ul>
                            <li>Gunakan koma (,) atau enter untuk memisahkan nomor seri</li>
                            <li>Contoh: 123456, 123457, 123458</li>
                        </ul>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="resetNomorSeri()">Keluar</button>
                <button type="button" id="simpandata" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    const resetNomorSeri = () => {
        $('#nomor_seri').val('');
    }

    $(document).on('click', '#simpandata', function () {
        let nomor_seri = $('#nomor_seri').val().split(/[\n, \t]/);

        nomor_seri = nomor_seri.map(function (item) {
            return item.trim();
        });

        nomor_seri = nomor_seri.filter(function (el) {
            return el != '';
        });

        nomor_seri = [...new Set(nomor_seri)];

        let nomorseritidakditemukan = [];

        let table = $('.scan-produk').DataTable();
        // using dt-checkboxes to true
        for (let i = 0; i < nomor_seri.length; i++) {
            let found = false;
            table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                let data = this.data();
                let nomor_seri_produk = data.noseri

                if (nomor_seri[i] == nomor_seri_produk) {
                    let checkbox = $(this.node()).find('input[type="checkbox"]');
                    checkbox.prop('checked', true);
                    found = true;
                }
            });

            if (!found) {
                nomorseritidakditemukan.push(nomor_seri[i]);
            }
        }
        if (nomorseritidakditemukan.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Nomor Seri Tidak Ditemukan',
                text: nomorseritidakditemukan.join(', '),
            })
        }

        resetNomorSeri();
        $('#modalautocheckbox').modal('hide');
    })
</script>
