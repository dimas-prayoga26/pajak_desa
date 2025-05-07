@extends('dashboard.layout.app')

@section('title', 'Detail Tagihan')

@section('css')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('breadcumb')
    <div class="page-header">
        <h1 class="my-auto page-title">Detail Tagihan</h1>
        <div>
            <ol class="mb-0 breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Detail Tagihan
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Detail Tagihan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Detail Tagihan</li>
                    </ol>
                </div> 
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="row w-100 align-items-center">
                        <!-- Kolom kiri: Judul & Filter Tahun -->
                        <div class="col-md-6 d-flex align-items-center gap-2">
                            <h3 class="card-title mb-0 mr-2">Data Detail Tagihan</h3>
                            {{-- <select class="form-control form-control-sm w-auto ml-3" id="filterTahun" name="tahun">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ $tahun == $tahunTerpilih ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select> --}}
                        </div>
                
                        <!-- Kolom kanan: Tombol Tambah Data -->
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahData">
                                <i class="fas fa-plus-circle"></i> Tambah Data
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- <div class="prism-toggle">
                    <button type="button" class="btn btn-sm btn-primary-light" data-bs-toggle="modal"
                        data-bs-target="#modalTambahData">
                        Tambah Data
                    </button>
                </div> --}}
                <div class="card-body">
                    <table id="datatable" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>jumlah Bayar</th>
                                <th>Status Bayar</th>
                                <th>Tahun</th>
                                <th>Pemberitahuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="modalTambahData" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
      
            <div class="modal-header">
              <h5 class="modal-title" id="modalLabel">Tambah Data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
      
            <form id="tambahData">
              <div class="modal-body">
      
                <div class="form-group">
                    <label for="name" class="form-label">Nama</label>
                    <select class="form-control" name="name" id="name">
                        <option value="">Pilih salah satu</option>
                    </select>                    
                </div>
      
                <div class="form-group">
                  <label for="nop">NOP</label>
                  <input type="text" class="form-control" id="nop" name="nop" placeholder="Masukkan Nomor Objek Pajak">
                </div>
      
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                </div>
      
                <div class="form-group">
                  <label for="luas_bumi">Luas Bumi (m²)</label>
                  <input type="number" step="0.01" class="form-control" id="luas_bumi" name="luas_bumi" placeholder="Masukkan luas bumi">
                </div>
      
                <div class="form-group">
                  <label for="luas_bangunan">Luas Bangunan (m²)</label>
                  <input type="number" step="0.01" class="form-control" id="luas_bangunan" name="luas_bangunan" placeholder="Masukkan luas bangunan">
                </div>
      
              </div>
      
              <div class="modal-footer">
                <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                <button type="button" class="btn btn-primary btn-sm" id="simpanData">
                  <i class="fe fe-save"></i> Simpan
                </button>
              </div>
            </form>
      
          </div>
        </div>
      </div>
      
      
    <!-- End Modal Tambah Data -->

    <!-- Edit Data -->
    <div class="modal fade" id="modalEditData" tabindex="-1" aria-labelledby="modalEditDataLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
      
            <div class="modal-header">
              <h6 class="modal-title" id="modalEditDataLabel">Edit Data</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
      
            <form id="editData">
              <div class="modal-body">
      
                <div class="form-group">
                  <label for="edit_user_id" class="form-label">Nama</label>
                  <select class="form-control" name="user_id" id="edit_user_id">
                    <option value="">Pilih salah satu</option>
                  </select>    
                </div>
      
                <div class="form-group">
                  <label for="edit_nop" class="form-label">NOP</label>
                  <input type="text" class="form-control" id="edit_nop" name="nop" placeholder="Masukkan Nomor Objek Pajak">
                </div>
      
                <div class="form-group">
                  <label for="edit_alamat" class="form-label">Alamat</label>
                  <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                </div>
      
                <div class="form-group">
                  <label for="edit_luas_bumi" class="form-label">Luas Bumi (m²)</label>
                  <input type="number" step="0.01" class="form-control" id="edit_luas_bumi" name="luas_bumi" placeholder="Masukkan luas bumi">
                </div>
      
                <div class="form-group">
                  <label for="edit_luas_bangunan" class="form-label">Luas Bangunan (m²)</label>
                  <input type="number" step="0.01" class="form-control" id="edit_luas_bangunan" name="luas_bangunan" placeholder="Masukkan luas bangunan">
                </div>
      
              </div>
      
              <div class="modal-footer">
                <button type="reset" class="btn btn-danger btn-sm" id="resetEditData">Reset</button>
                <button type="button" class="btn btn-primary btn-sm" id="updateData">
                  <i class="fe fe-save"></i> Simpan
                </button>
              </div>
            </form>
      
          </div>
        </div>
      </div>
      
      

    <!-- End -->

@endsection

@section('js')

    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>

        $('.select2').select2();
        var table;

        $('#modalTambahData').on('shown.bs.modal', function () {
            $('#name').select2({
                placeholder: 'Cari nama wajib pajak...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modalTambahData'),
                ajax: {
                    url: "{{ route('detail-pajak.user-options') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id: item.id,
                                    text: `${item.nama}`
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $(document).ready(function () {
            table = $("#datatable").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('detail-pajak.datatable') }}",
                    // data: function (d) {
                        
                    //     d.tahun = $('#filterTahun').val(); // ambil nilai filter tahun
                    // }
                },
                columnDefs: [
                    {
                        targets: 0,
                        render: function (data, type, full, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        targets: 1,
                        render: function (data, type, full, meta) {
                            return full.user && full.user.biodata && full.user.biodata.nama
                                ? full.user.biodata.nama
                                : '-';
                        }
                    },
                    {
                        targets: 4,
                        render: function (data) {
                            return `${parseFloat(data).toFixed(2)} m²`;
                        }
                    },
                    {
                        targets: 5,
                        render: function (data) {
                            return `${parseFloat(data).toFixed(2)} m²`;
                        }
                    },
                    {
                        targets: 6,
                        render: function (data, type, full, meta) {
                            if (data === 'belum') {
                                return `
                                    <button type="button" class="btn btn-info btn-sm" onclick="kirimPemberitahuan(${full.id})">
                                        Kirim Pemberitahuan
                                    </button>
                                `;
                            } else if (data === 'dibayar') {
                                return `<span class="badge badge-success">Sudah Dibayarkan</span>`;
                            } else {
                                return `<span class="badge badge-secondary">Status Tidak Diketahui</span>`;
                            }
                        }
                    },

                    {
                        targets: 7,
                        render: function (data, type, full, meta) {
                            // console.log(data);
                            
                            return `
                                <button type="button" class="btn btn-warning btn-sm" onclick="editData(${data})">
                                    <i class="fe fe-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="hapusData(${full.id})">
                                    <i class="fe fe-trash"></i> Hapus
                                </button>
                            `;
                        }
                    }

                ],
                columns: [
                    { data: null },
                    { data: 'user_id' },
                    { data: 'nop' },
                    { data: 'alamat' },
                    { data: 'luas_bumi' },
                    { data: 'luas_bangunan' },
                    { data: 'status_bayar' },
                    { data: 'id' }
                ],
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: ''
                }
            });

            $('#filterTahun').on('change', function () {
                table.ajax.reload();
            });
        });


        function editData(id) {
            console.log(id);

                $.ajax({
                    url: "{{ url('super-admin/detail-pajak') }}/" + id,
                    type: "GET",
                    success: function(response) {
                        if (response.status === true) {
                            const data = response.data;
                            console.log(data);
                            
                            // $('#editNama').val(data.user_id).trigger('change');
                            NameOptions(data.user_id);
                            $('#edit_nop').val(data.nop);
                            $('#edit_alamat').val(data.alamat);
                            $('#edit_luas_bumi').val(data.luas_bumi);
                            $('#edit_luas_bangunan').val(data.luas_bangunan);
                            $('#editData').data('id', id);
                            $('#modalEditData').modal('show');
                        } else {
                            toastr.warning('Data tidak ditemukan');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            }

            function NameOptions(selectedId = null) {
                $.ajax({
                    url: "{{ route('detail-pajak.user-options') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        const $select = $('#edit_user_id');
                        $select.empty().append('<option value="">Pilih nama wajib pajak</option>');

                        $.each(response, function (i, item) {
                            console.log(item);
                            
                            $select.append(`<option value="${item.id}">${item.nama}</option>`);
                        });

                        if (selectedId) {
                            $select.val(selectedId).trigger('change');
                        }
                    },
                    error: function () {
                        toastr.error('Tidak dapat mengambil data user dari server.', 'Gagal');
                    }
                });
            }

            function hapusData(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('super-admin/detail-pajak') }}/" + id,
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.status === true) {
                                    toastr.success(response.message, 'Berhasil');
                                    table.ajax.reload();
                                } else {
                                    toastr.warning('Data tidak ditemukan.', 'Gagal');
                                }
                            },
                            error: function(xhr) {
                                let message = xhr.responseJSON?.message || xhr.responseText;
                                toastr.error(message, 'Terjadi Kesalahan');
                            }
                        });
                    }
                });
            }



        $("#simpanData").on("click", function(e) {
            e.preventDefault();

            let formData = {
                name: $("#name").val(),
                nop: $("#nop").val(),
                alamat: $("#alamat").val(),
                luas_bumi: $("#luas_bumi").val(),
                luas_bangunan: $("#luas_bangunan").val()
            };

            $.ajax({
                url: "{{ url('super-admin/detail-pajak') }}", // sesuaikan URL jika perlu
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === true) {
                        toastr.success(response.message, 'Berhasil');
                        $("#tambahData")[0].reset();
                        $('#modalTambahData').modal('hide');
                        table.ajax.reload();
                    } else {
                        toastr.warning(response.message || 'Data tidak berhasil diproses.', 'Peringatan');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data.';
                    toastr.error(message, 'Gagal');
                }
            });
        });


        $("#updateData").on("click", function(e) {
            e.preventDefault();

            let id = $("#editData").data('id');

            let formData = {
                user_id: $("#edit_user_id").val(),
                nop: $("#edit_nop").val(),
                alamat: $("#edit_alamat").val(),
                luas_bumi: $("#edit_luas_bumi").val(),
                luas_bangunan: $("#edit_luas_bangunan").val(),
            };

            $.ajax({
                url: "{{ url('super-admin/detail-pajak') }}/" + id,
                type: "PUT",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === true) {
                        toastr.success(response.message, 'Berhasil');
                        $("#editData")[0].reset();
                        $('#modalEditData').modal('hide');
                        table.ajax.reload();
                    } else {
                        toastr.warning(response.message || 'Update gagal.', 'Peringatan');
                    }
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || xhr.responseText || 'Terjadi kesalahan saat mengupdate data.';
                    toastr.error(message, 'Gagal');
                }
            });
        });



    </script>
@endsection
