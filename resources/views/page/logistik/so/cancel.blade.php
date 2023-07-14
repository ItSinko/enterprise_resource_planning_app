<div class="row filter">
    <div class="col-12">
        <div class="card card-navy card-outline card-tabs">
            <form id="batal_so_log" action="/api/logistik/so/cancel" method="POST">
                @csrf
            <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <input class="d-none" name="id" value="{{$id}}" hidden/>
                                <input class="d-none" name="user" value="{{Auth::user()->id}}" hidden/>
                                <textarea class="form-control" placeholder="Masukkan alasan membatalkan transaksi {{$data->no_po}}" name="alasan" ></textarea>
                            </div>
                        </div>
            </div>
            <div class="card-footer">
                @if(Auth::user()->Divisi->nama == "Logistik")
                <button type="submit" class="btn btn-danger btn-sm float-right"><i class="fas fa-check"></i> Konfirmasi</button>
                @endif
                  </div>
        </form>
        </div>
    </div>
</div>
