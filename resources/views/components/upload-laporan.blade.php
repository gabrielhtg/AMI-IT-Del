<a href="#modal-add-laporan" class="btn btn-success mb-3" data-toggle="modal">
    <i class="fas fa-plus"></i> <span style="margin-left: 5px">Tambah Laporan</span>
</a>

<!-- Modal -->
<div class="modal fade" id="modal-add-laporan">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Laporan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulir -->
                <form id="form-add-laporan" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="nama-laporan">Nama Laporan</label>
                        <input type="text" class="form-control" placeholder="Ketik disini" id="nama-laporan" name="nama_laporan" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="tipe-laporan">Tipe Laporan</label>
                        <select id="tipe-laporan" name="id_tipelaporan" 
                                class="tipe-laporan-custom form-control" style="width: 100%">
                            <option></option>
                            @foreach($tipe_laporan as $tipe)
                                <option value="{{ $tipe->id }}">{{ $tipe->nama}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="revisi">Revisi:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="revisiCheckbox" name="revisi" value="1">
                            <label class="form-check-label" for="revisiCheckbox">Ya</label>
                        </div>
                    </div>

                    <div class="form-group mt-3" style="display: none;">
                        <label for="menggantikan">Merevisi Laporan:</label>
                        <select id="menggantikan" name="cek_revisi" 
                                class="menggantikan-to-custom form-control" style="width: 100%">
                            <option></option>
                            @php
                            $allServices = new \App\Services\AllServices();
                            @endphp
                            @foreach ($laporan as $item)
                                @if(auth()->user()->id == $item->created_by && $item->status=="Ditolak" && $allServices->isLaporanIdInCekLaporan($item->id))
                                    <option value="{{$item->id}}">{{$item->nama_laporan}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="document-file">Dokumen</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="document-file">
                                <label class="custom-file-label" for="document-file">Pilih file</label>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Script JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var checkbox = document.getElementById('revisiCheckbox');
        var merevisiLaporan = document.getElementById('menggantikan').parentNode;

        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                merevisiLaporan.style.display = 'block';
            } else {
                merevisiLaporan.style.display = 'none';
            }
        });
    });
</script>