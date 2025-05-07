@extends('dashboard.layout.app')

@section('title', 'Detail Akun')

@section('css')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('breadcumb')
    <div class="page-header">
        <h1 class="my-auto page-title">Detail Akun</h1>
        <div>
            <ol class="mb-0 breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Detail Akun
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
                    <h1>Detail Akun</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Detail Akun</li>
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
                            <h3 class="card-title mb-0 mr-2">Data Detail Akun</h3>
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
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>Nomor Hp</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
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
      
            <form id="tambahData" enctype="multipart/form-data">
              <div class="modal-body">
      
                <div class="form-group">
                  <label for="nama">Nama</label>
                  <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap">
                </div>
      
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email">
                </div>
      
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username">
                </div>
      
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
                </div>
      
                <div class="form-group">
                  <label for="no_hp">Nomor HP</label>
                  <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan nomor HP">
                </div>
      
                <div class="form-group">
                  <label for="tanggal_lahir">Tanggal Lahir</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fi fi-rr-calendar-day"></i></span>
                    </div>
                    <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="dd/mm/yyyy" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                  </div>
                </div>
      
                <div class="form-group">
                  <label for="jenis_kelamin">Jenis Kelamin</label>
                  <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                  </select>
                </div>
      
                <div class="form-group">
                  <label for="alamat">Alamat</label>
                  <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                </div>
      
                <div class="form-group">
                    <label for="file_upload">Foto Profile</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file_upload" name="file_upload" accept="image/*" />
                            <label class="custom-file-label" for="file_upload">Pilih file</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                        </div>
                    </div>
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

    <div class="modal fade" id="modalEditData" tabindex="-1" aria-labelledby="modalEditDataLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
      
            <div class="modal-header">
              <h6 class="modal-title" id="modalEditDataLabel">Edit Data</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
      
            <form id="editData" enctype="multipart/form-data">
              <div class="modal-body">
      
                <div class="form-group">
                  <label for="edit_nama">Nama</label>
                  <input type="text" class="form-control" id="edit_nama" name="nama" placeholder="Masukkan nama lengkap">
                </div>
      
                <div class="form-group">
                  <label for="edit_email">Email</label>
                  <input type="email" class="form-control" id="edit_email" name="email" placeholder="Masukkan email">
                </div>
      
                <div class="form-group">
                  <label for="edit_username">Username</label>
                  <input type="text" class="form-control" id="edit_username" name="username" placeholder="Masukkan username">
                </div>
      
                <div class="form-group">
                  <label for="edit_password">Password</label>
                  <input type="password" class="form-control" id="edit_password" name="password" placeholder="Masukkan password baru">
                  <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                </div>
      
                <div class="form-group">
                  <label for="edit_no_hp">Nomor HP</label>
                  <input type="text" class="form-control" id="edit_no_hp" name="no_hp" placeholder="Masukkan nomor HP">
                </div>
      
                <div class="form-group">
                  <label for="edit_tanggal_lahir">Tanggal Lahir</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fi fi-rr-calendar-day"></i></span>
                    </div>
                    <input type="text" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir" placeholder="dd/mm/yyyy" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                  </div>
                </div>
      
                <div class="form-group">
                  <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                  <select class="form-control" name="jenis_kelamin" id="edit_jenis_kelamin">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                  </select>
                </div>
      
                <div class="form-group">
                  <label for="edit_alamat">Alamat</label>
                  <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                </div>
      
                <div class="form-group">
                  <label for="edit_file">Foto Profile</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="edit_file" name="file_upload"  accept="image/*">
                      <label class="custom-file-label" for="edit_file">Pilih file</label>
                    </div>
                    <div class="input-group-append">
                      <span class="input-group-text">Upload</span>
                    </div>
                  </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" 
        integrity="sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA=" 
        crossorigin="anonymous"></script>


    <script>

        $(function () {
            $('input[name="tanggal_lahir"]').mask('00/00/0000'); 

            $(document).on('change', '.custom-file-input', function (e) {
                if (e.target.files.length > 0) {
                    var fileName = e.target.files[0].name;
                    $(this).next('.custom-file-label').html(fileName);
                }
            });
        });


        var table;

        $(document).ready(function () {
            table = $("#datatable").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('user.datatable') }}",
                },
                columnDefs: [
                    {
                        targets: 0,
                        render: function (data, type, full, meta) {
                            console.log(full);
                            
                            return meta.row + 1;
                        }
                    },
                    {
                        targets: 3,
                        render: function (data, type, full, meta) {
                            return full.user.email || '-';
                        }
                    },
                    {
                        targets: 7,
                        render: function (data, type, full, meta) {
                            return `
                                <button type="button" class="btn btn-warning btn-sm" onclick="editData(${data})">
                                    <i class="fe fe-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="hapusData(${full.user_id})">
                                    <i class="fe fe-trash"></i> Hapus
                                </button>
                            `;
                        }
                    }

                ],
                columns: [
                    { data: null },
                    { data: 'nama' },
                    { data: 'alamat' },
                    { data: null },
                    { data: 'no_hp' },
                    { data: 'tanggal_lahir' },
                    { data: 'jenis_kelamin' },
                    { data: 'id' }
                ],
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: ''
                }
            });
        });

        $("#no_hp").on('input', function() {
            var value = $(this).val();
            
            // Allow only numbers
            value = value.replace(/[^0-9]/g, ''); // Removes non-numeric characters
            $(this).val(value);  // Update the field with the cleaned value

            // Limit the number of characters to 13
            if (value.length > 13) {
                $(this).val(value.substring(0, 13));
            }
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
                            url: "{{ url('super-admin/user') }}/" + id,
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

            $("#simpanData").on("click", function (e) {
                e.preventDefault();

                // Validate Nomor HP (10 - 13 digits, numbers only)
                var nomorHp = $("#no_hp").val();
                var phoneRegex = /^[0-9]{10,13}$/;  // Only numbers, 10 to 13 digits

                if (!phoneRegex.test(nomorHp)) {
                    toastr.warning('Nomor HP harus berupa angka dengan panjang 10 hingga 13 digit.', 'Peringatan');
                    $('#no_hp').focus();
                    return;  // Stop the function if validation fails
                }

                // Create FormData object
                let formData = new FormData();

                formData.append("nama", $("#nama").val());
                formData.append("email", $("#email").val());
                formData.append("username", $("#username").val());
                formData.append("password", $("#password").val());
                formData.append("no_hp", nomorHp);  // Use validated Nomor HP
                formData.append("tanggal_lahir", $("#tanggal_lahir").val());
                formData.append("jenis_kelamin", $("#jenis_kelamin").val());
                formData.append("alamat", $("#alamat").val());

                // If a file is selected, append it to the form data
                let fileUpload = $("#file_upload")[0].files[0];
                if (fileUpload) {
                    formData.append("file_upload", fileUpload);
                }

                // Send the data via AJAX
                $.ajax({
                    url: "{{ url('super-admin/user') }}", // Sesuaikan dengan URL endpoint Anda
                    type: "POST",
                    data: formData,
                    processData: false,  // Don't process data
                    contentType: false,  // Don't set content type
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === true) {
                            toastr.success(response.message, 'Berhasil');
                            $("#tambahData")[0].reset();
                            $('#modalTambahData').modal('hide');
                            table.ajax.reload();
                        } else {
                            toastr.warning(response.message || 'Data tidak berhasil diproses.', 'Peringatan');
                        }
                    },
                    error: function (xhr) {
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
