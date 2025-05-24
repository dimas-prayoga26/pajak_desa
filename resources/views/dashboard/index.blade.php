@extends('dashboard.layout.app')

@section('title', 'Testimonials Product')

@section('css')

@section('content')
<!-- Default box -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      @role('warga')
        <div class="row mb-3">
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" id="nopSearch" class="form-control" placeholder="Cari NOP...">
              <div class="input-group-append">
                <button class="btn btn-primary" id="searchButton">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      @endrole


      {{-- Untuk superAdmin --}}
      @role('superAdmin')
      <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $totalUser }}</h3>
                <p>Total User</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="{{ route('user.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $totalWajibPajak }}</h3>
                <p>Total Data Pajak</p>
              </div>
              <div class="icon">
                <i class="ion ion-document-text"></i>
              </div>
              <a href="{{ route('detail-pajak.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>Rp{{ number_format($totalHariIni, 0, ',', '.') }}</h3>
                <p>Uang Masuk Hari Ini</p>
              </div>
              <div class="icon">
                <i class="ion ion-cash"></i>
              </div>
              <a href="{{ route('riwayat-pajak.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</h3>
                <p>Total Uang Masuk</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="{{ route('riwayat-pajak.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
      </div>
      @endrole

      {{-- Untuk warga --}}
      @role('warga')
      <div class="row">
          <div class="col-lg-4 col-6">
              <div class="small-box bg-primary">
                  <div class="inner">
                      <h3>{{ $nopSubscribed }}</h3>
                      <p>NOP yang Disubscribe</p>
                  </div>
                  <div class="icon">
                      <i class="ion ion-home"></i>
                  </div>
                  <a href="{{ route('detail-tagihan.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
          </div>

          <div class="col-lg-4 col-6">
              <div class="small-box bg-success">
                  <div class="inner">
                      <h3>{{ $jumlahSudahBayar }}</h3>
                      <p>Tagihan Sudah Dibayar</p>
                  </div>
                  <div class="icon">
                      <i class="ion ion-checkmark-circled"></i>
                  </div>
                  <a href="{{ route('riwayat-pajak.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
          </div>

          <div class="col-lg-4 col-6">
              <div class="small-box bg-danger">
                  <div class="inner">
                      <h3>{{ $jumlahBelumBayar }}</h3>
                      <p>Tagihan Belum Dibayar</p>
                  </div>
                  <div class="icon">
                      <i class="ion ion-close-circled"></i>
                  </div>
                  <a href="{{ route('riwayat-pajak.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
              </div>
          </div>
      </div>
      @endrole


    </div>
  </section>

  <!-- Modal -->
  <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Ditambahkan modal-lg -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="searchModalLabel">Hasil Pencarian</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Nop</th>
                <th>Alamat</th>
                <th>Luas Bumi</th>
                <th>Luas Bangunan</th>
              </tr>
            </thead>
            <tbody id="detailTagihanBody">
              <!-- Data akan diisi via JavaScript -->
            </tbody>
          </table>
        </div>
        <hr>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="addHakMilik">Tambah</button>
        </div>
      </div>
    </div>
  </div>


<!-- /.card -->
@endsection

@section('js')

    <script>
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        $(document).ready(function () {
          $('#searchButton').click(function () {
            let nop = $('#nopSearch').val();

            $.ajax({
              url: "{{ route('dashboard.search.nop') }}",
              type: "GET",
              data: { nop: nop },
              success: function (response) {
                console.log(response);
                
                let tbody = '';
                let owned = false;
                owned = response[0].user_id !== null;

                if (response.length === 0) {
                  tbody = '<tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>';
                } else {
                  $.each(response, function (i, item) {
                    tbody += `
                      <tr>
                        <td>${i + 1}</td>
                        <td>${item.name}</td>
                        <td>${item.nop}</td>
                        <td>${item.alamat}</td>
                        <td>${item.luas_bumi} m²</td>
                        <td>${item.luas_bangunan} m²</td>
                      </tr>
                    `;
                  });
                }
                $('#detailTagihanBody').html(tbody);
                $('#searchModalLabel').text(`Hasil Pencarian NOP: ${nop}`);
                $('#addHakMilik')
                .text(owned ? 'Batalkan Kepemilikan' : 'Tambah')
                .attr('data-mode', owned ? 'unsubscribe' : 'subscribe')
                .removeClass('btn-primary btn-danger')
                .addClass(owned ? 'btn-danger' : 'btn-primary');

                $('#searchModal').modal('show');
              },
              error: function () {
                alert('Gagal mengambil data. Coba lagi.');
              }
            });
          });

          $('#addHakMilik').click(function () {
            let nop = $('#nopSearch').val();
            let mode = $(this).attr('data-mode');

            if (mode === 'unsubscribe') {
              Swal.fire({
                title: 'Batalkan Kepemilikan?',
                text: "Data akan dilepas dari akun Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Batal'
              }).then((result) => {
                if (result.isConfirmed) {
                  prosesUpdateHakMilik(nop, mode); 
                }
              });
            } else {
              prosesUpdateHakMilik(nop, mode);
            }
          });

          function prosesUpdateHakMilik(nop, mode) {
            $.ajax({
              url: "{{ route('dashboard.update-user') }}",
              type: "POST",
              data: {
                nop: nop,
                mode: mode,
                _token: "{{ csrf_token() }}"
              },
              success: function (response) {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  text: response.message,
                  timer: 2000,
                  showConfirmButton: false
                });
                $('#searchModal').modal('hide');
              },
              error: function (xhr) {
                let msg = 'Gagal memperbarui data.';
                if (xhr.status === 403 || xhr.status === 404) {
                  msg = xhr.responseJSON?.message || msg;
                }
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: msg
                });
              }
            });
          }



        });
    </script>
@endsection