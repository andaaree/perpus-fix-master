<!DOCTYPE html>
@extends('/admin/body')
@section('title', 'Buku - Admin Perpus')
@section('ext-css')
<!-- Select2 -->
<link rel="stylesheet" href="/assets/adminlte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<link rel="stylesheet" href="/assets/css/admin.css">

@endsection
@section('csrf-ajax')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('container')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="display-4">Daftar buku</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Buku</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@php
    function numberToRomanRepresentation($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
    foreach ($map as $roman => $int) {
    if($number >= $int) {
    $number -= $int;
    $returnValue .= $roman;
    break;
    }
    }
    }
    return $returnValue;
    }
    @endphp
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-10">
                <!-- general form elements -->
                <div class="card">
                    <div class="card-header">
                        <button data-target="#modal-add" data-toggle="modal" class="btn btn-dark">Tambah buku</button>
                    </div>
                    <div class="card-body">
                        {{-- <div class="table-responsive"> --}}
                        <table id="tb-book" class="table table-bordered table-striped">
                            <thead>
                                <th>No</th>
                                <th>ID</th>
                                <th>Judul Buku</th>
                                <th>Tahun Terbit</th>
                                <th>Penerbit</th>
                                <th>Pengarang</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" aria-modal="true" id="modal-add" aria-hidden="false" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="fdata" action="{{ route("buku.store") }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1>Tambah buku</h1>
                    </div>
                    <div class="modal-body">
                        <p class="text-red">*) Pastikan seluruh input terisi</p>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab" aria-controls="file" aria-selected="true">File</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-toggle="tab" href="#tab2" role="tab" aria-controls="sekolah" aria-selected="false">Sekolah</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-toggle="tab" href="#tab3" role="tab" aria-controls="buku" aria-selected="false">Buku</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1">
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label" for="filebook">Upload File</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <label for="" class="custom-file-label">Pilih PDF</label>
                                                    @csrf
                                                    <input type="file" name="filebook" id="filebook" class="custom-file-input @error('filebook'){{'has-danger'}}@enderror" value="{{old('filebook')}}" accept=".pdf">
                                                    <input hidden type="text" name="thumb" id="thumb" class="custom-file-input @error('thumb'){{'is-invalid'}}@enderror" value="{{old('thumb')}}" accept=".pdf">
                                                    @error('filebook')
                                                    <div class="form-control-feedback">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <canvas id="pdfViewer"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="jenjang">Jenjang</label>
                                            <div class="input-group">
                                                <select class="form-control select2bs4 @error('jenjang'){{'is-invalid'}}@enderror" name="jenjang" id="jenjang" aria-label="Example select with button addon">
                                                    <option value="">-- Pilih Jenjang --</option>
                                                    @foreach ($edu as $e)
                                                    <option @if(old('jenjang')==$e->id){{ 'selected' }}@endif value="{{ $e->id }}">{{ $e->edu_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('jenjang')
                                                <div class="invalid-feedback">
                                                    {{$message}}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="kelas">Kelas</label>
                                            <div class="input-group">
                                                <select name="kelas" class="form-control select2bs4 @error('kelas'){{ 'is-invalid' }}@enderror" id="kelas" aria-label="">
                                                    <option value="">-- Pilih Kelas --</option>
                                                    @for ($i = 1; $i < 13; $i++) 
                                                    <option @if(old('kelas')==$i){{ 'selected' }}@endif value="{{ $i }}">{{ numberToRomanRepresentation($i) }}</option>
                                                    @endfor
                                                </select>
                                                @error('kelas')
                                                <div class="invalid-feedback">
                                                    {{$message}}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="sekolah">Jurusan</label>
                                            <div class="input-group">
                                                <select name="jurusan" class="form-control select2bs4 @error('jurusan'){{ 'is-invalid' }}@enderror"" id=" jurusan" aria-label="">
                                                    <option value="">-- Pilih Jurusan --</option>
                                                    @foreach ($maj as $m )
                                                    <option @if(old('jurusan')==$m->id){{ 'selected' }}@endif value="{{ $m->id }}">{{ $m->maj_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('jurusan')
                                                <div class="invalid-feedback">
                                                    {{$message}}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="mapel">Mata Pelajaran</label>
                                            <div class="input-group">
                                                <select name="mapel" class="form-control select2bs4 @error('mapel'){{ 'is-invalid' }}@enderror"" id=" mapel" aria-label="">
                                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                                    @foreach ($sub as $sbj )
                                                    <option @if(old('mapel')==$sbj->id){{ 'selected' }}@endif value="{{ $sbj->id }}">{{ $sbj->sbj_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('mapel')
                                                <div class="invalid-feedback">
                                                    {{$message}}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="judul">Judul Buku</label>
                                            <input type="text" name="judul" id="judul" class="form-control @error('judul'){{'is-invalid'}}@enderror" value="{{old('judul')}}">
                                            @error('judul')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="desc">Deskripsi</label>
                                            <input type="text" name="desc" id="desc" class="form-control @error('desc'){{'is-invalid'}}@enderror" value="{{old('desc')}}">
                                            @error('desc')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="tahun">Tahun Terbit</label>
                                            <input type="text" name="tahun" id="tahun" class="form-control @error('tahun'){{'is-invalid'}}@enderror" value="{{old('tahun')}}">
                                            @error('tahun')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="penerbit">Penerbit</label>
                                            <input type="text" name="penerbit" id="penerbit" class="form-control @error('penerbit'){{'is-invalid'}}@enderror" value="{{old('penerbit')}}">
                                            @error('penerbit')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="pengarang">Pengarang</label>
                                            <input type="text" name="pengarang" id="pengarang" class="form-control @error('pengarang'){{'is-invalid'}}@enderror" value="{{old('pengarang')}}">
                                            @error('pengarang')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" id="save-book" class="btn btn-secondary">Tambah</button>
                    </div>
                </form>  
            </div>
        </div>
    </div>
</section>
@endsection
@section('ext-script')
<!-- bs-custom-file-input -->

<script src="/assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="/assets/adminlte/plugins/select2/js/select2.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/assets/adminlte/plugins/jszip/jszip.min.js"></script>
<script src="/assets/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/assets/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- SweetAlert2 -->
<script src="/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>

<!--Javascript Admin -->
<script src="/assets/js/admin.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<!-- Page specific script -->
<script>
    $(document).ready(function() {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        bsCustomFileInput.init();

        var table = $('#tb-book').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "columnDefs": [{
                targets: [1],
                visible: false,
                searchable: false
            }],
            "columns": [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'details-control',
                    responsivePriority: 1
                },
                {
                    data: "id",
                    name: "id",
                    orderable: false
                },
                {
                    data: "title",
                    name: "title"
                },
                {
                    data: "published_year",
                    name: "published_year"
                },
                {
                    data: "publisher",
                    name: "publisher"
                },
                {
                    data: "author",
                    name: "author"
                },
                {
                    defaultContent: '<button type="button" class="edit-book btn btn-success"><i class="fas fa-edit"></i></button> <button type="button" class="d-inline del-book btn btn-danger"><i class="fas fa-trash"></i></button>'
                }
            ],
            "ajax": "/buku/all"
        });
        $('#tb-book tbody').on('click', '.edit-book', function(e) {
            e.preventDefault;
            var id = $(this).closest('tr').attr('id');
            window.location.href = "buku/" + id + "/edit";
        });
        $('#tb-book tbody').on('click', '.del-book', function(e) {
            e.preventDefault;
            var id = $(this).closest('tr').attr('id');
            Swal.fire({
                title: 'Yakin hapus?',
                text: "Anda tidak bisa kembalikan data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        url: "/admin/buku/" + id,
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            console.log(data);
                            Swal.fire({
                                icon: data.status,
                                title: data.title,
                                text: data.message,
                                timer: 1200
                            });
                            table.draw();
                        },
                        error: function(data) {
                            console.log(data);
                            var js = data.responseJSON;
                            Swal.fire({
                                icon: 'error',
                                title: js.exception,
                                text: js.message,
                                timer: 1200
                            });
                        }
                    });
                }
            });
        });
        // Loaded via <script> tag, create shortcut to access PDF.js exports.
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        var dataURL = null;
        // The workerSrc property shall be specified.
        pdfjsLib.GlobalWorkerOptions.workerSrc = '/assets/js/pdf.worker.js';
        
        $("#filebook").on("change", function(e){
            var file = e.target.files[0];
            var canvas = $("#pdfViewer")[0];
            if(file.type == "application/pdf"){
                var fileReader = new FileReader();
                fileReader.onload = function() {
                    var pdfData = new Uint8Array(this.result);
                    // Using DocumentInitParameters object to load binary data.
                    var loadingTask = pdfjsLib.getDocument({data: pdfData});
                    loadingTask.promise.then(function(pdf) {
                        // console.log('PDF loaded');
                        
                        // Fetch the first page
                        var pageNumber = 1;
                        pdf.getPage(pageNumber).then(function(page) {
                            // console.log('Page loaded');
                            
                            var scale = 0.35;
                            var viewport = page.getViewport({scale:scale});

                            // Prepare canvas using PDF page dimensions
                            var context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            // Render PDF page into canvas context
                            var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                            };
                            var renderTask = page.render(renderContext);
                            renderTask.promise.then(function () {
                            dataURL = canvas.toDataURL("image/png");
                            $("#thumb").val(dataURL);
                            {{-- // console.log('Page rendered');
                            // console.log(dataURL);
                            --}}
                            });
                        });
                    }, function (reason) {
                    // PDF loading error
                    console.error(reason);
                    });

                };
                fileReader.readAsArrayBuffer(file);
            }
        });
    });
</script>
@include('admin.validator')
@endsection