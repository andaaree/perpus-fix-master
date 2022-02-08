@extends('layouts.main')
@section('ext-css')
<!-- Select2 -->
<link rel="stylesheet" href="/assets/adminlte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="/assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection
@section('container')
<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Buku</h2>
      <ol>
        <li><a href="/">Home</a></li>
        <li>Buku</li>
      </ol>
    </div>
  </div>
</section>
<!-- End Breadcrumbs -->

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

<!-- ======= Buku Single Section ======= -->
<section id="file" class="file">
  <div class="container" data-aos="fade-up">
    <div class="content-list" id="file-terbaru">
      <div class="row">
        <!-- Search Sidebar -->
        <aside class="col-lg-3 mb-1">
          <button type="button" class="btn-lg btn-filter d-lg-none d-block" id="filterbtn">
            <center>
              <i class="ri-filter-fill"></i>
              <span>Filter</span>
            </center>
          </button>

          <div class="sidebar d-lg-block d-none mh-75% overflow-auto">
            <h3 class="sidebar-title">Search</h3>
            <div class="sidebar-item search-form">
              <form action="">
                <input type="text">
                <button type="submit">
                  <i class="bi bi-search"></i>
                </button>
              </form>
            </div>
            <!-- End sidebar search formn-->
            <h3 class="sidebar-title">Jenjang</h3>
            <div class="sidebar-item filter">
              <!-- jenjang -->
              @foreach($edu as $e)
              <div class="form-check">
                <input value="{{ $e->id }}" class="form-check-input" type="radio" name="jenjang" id="jenjang" />
                <label class="form-check-label" for="jenjang">
                  {{ $e->edu_name }}
                </label>
              </div>
              @endforeach
            </div>
              <!-- kelas -->
              <h3 class="sidebar-title">Kelas</h3>
              <div class="sidebar-item filter">
                <div class="row">
                  <div class="col-6">
                    @for ($i = 1; $i < 7; $i++) 
                      <div class="form-check">
                        <input value="{{ $i }}" class="form-check-input" type="radio" name="kelas" id="kelas" />
                        <label class="form-check-label" for="kelas">
                          {{ numberToRomanRepresentation($i) }}
                        </label>
                      </div>
                    @endfor
                  </div>
                  <div class="col-6">
                    @for ($i = 7; $i < 13; $i++) 
                      <div class="form-check">
                        <input value="{{ $i }}" class="form-check-input" type="radio" name="kelas" id="kelas" />
                        <label class="form-check-label" for="kelas">
                          {{ numberToRomanRepresentation($i) }}
                        </label>
                      </div>
                    @endfor
                  </div>
                </div>
              </div>
            <!-- mapel -->
            <h3 class="sidebar-title" for="mapel">Mata Pelajaran</h3>
            <div class="sidebar-item filter">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <!-- <label class="form-label" for="mapel">Mata Pelajaran</label> -->
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
            <!-- End sidebar filter-->
            <div class="row justify-content-center">
              <div class="col-12">
                <a class="btn-hapus" href="#filterbtn">
                  <span>Terapkan</span>
                </a>
                <a class="btn-hapus" href="#filterbtn">
                  <span>Reset</span>
                </a>
              </div>
            </div>
          </div>
          <!-- End sidebar -->
        </aside>


        <div class="col-lg-9 entries">
          <div class="row">

            <!-- File -->
            @foreach ($book as $b)
              <div class="col-lg-3 col-md-4 col-sm-6 ml-3 mb-3">
                <div class="member">
                  <div class="member-img">
                    <img src="/assets/perpus/assets/img/coverbuku.png" class="img-fluid" alt="" />
                    <div class="social">
                      <a href="/assets/perpus/assets/pdf/example.pdf"><i class="ri-file-download-fill"></i></a>
                      <a href="/web/viewer.html?file={{ $b->filename }}"><i class="ri-eye-fill"></i></a>
                    </div>
                  </div>
                  <div class="member-info">
                    <h5>Matematika <br />Kelas 1 SMP</h5>
                    <div class="btn-file">
                      <span>PDF</span>
                    </div>
                    <p>
                      Deskripsi Buku Deskripsi Buku Deskripsi Buku Deskripsi
                      Buku
                    </p>
                    <div class="stat-content">
                      <a href="#">dilihat 120 kali</a>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach

          <!-- Pagination -->
          <nav aria-label="...">
            <ul class="pagination justify-content-center mt-3">
              <li><a href="#">&laquo;</a></li>
              <li class="active"><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">&raquo;</a></li>
            </ul>
          </nav>
        </div>
        <!-- End row buku entries list -->
      </div>
      <!-- End buku sidebar -->
    </div>
  </div>
  </div>
</section>
<!-- End Buku Single Section -->
</main>
<!-- End #main -->
@endsection
@section('ext-js')
<script src="/assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="/assets/adminlte/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  });
</script>
@endsection