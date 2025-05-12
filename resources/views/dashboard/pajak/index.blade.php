@extends('dashboard.layout.app')

@section('title', 'Detail Pajak')

@section('css')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('breadcumb')
    <div class="page-header">
        <h1 class="my-auto page-title">Detail Pajak</h1>
        <div>
            <ol class="mb-0 breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Detail Pajak
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
                    <h1>Detail Pajak</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Detail Pajak</li>
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
                            <h3 class="card-title mb-0 mr-2">Data Detail Pajak</h3>
                            {{-- <select class="form-control form-control-sm w-auto ml-3" id="filterTahun" name="tahun">
                                <option value="">Semua Tahun</option>
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ $tahun == $tahunTerpilih ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select> --}}
                        </div>

                        <!-- Kolom kanan: Tombol Tambah Data -->
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalTambahData">
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
                                <th>Nop</th>
                                <th>Alamat</th>
                                <th>Luas Bumi</th>
                                <th>Luas Bangunan</th>
                                <th>Status Pembayaran</th>
                                {{-- <th>Pemberitahuan</th> --}}
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
    <div class="modal fade" id="modalTambahData" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
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
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama">
                        </div>

                        <div class="form-group">
                            <label for="nop">NOP</label>
                            <input type="text" class="form-control" id="nop" name="nop"
                                placeholder="Masukkan Nomor Objek Pajak">
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="luas_bumi">Luas Bumi (m²)</label>
                            <input type="number" step="0.01" class="form-control" id="luas_bumi" name="luas_bumi"
                                placeholder="Masukkan luas bumi">
                        </div>

                        <div class="form-group">
                            <label for="luas_bangunan">Luas Bangunan (m²)</label>
                            <input type="number" step="0.01" class="form-control" id="luas_bangunan"
                                name="luas_bangunan" placeholder="Masukkan luas bangunan">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                        <button type="button" class="btn btn-primary btn-sm" id="simpanData">
                            <i class="fe fe-save"></i> Selanjutnya
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTagihan" tabindex="-1" role="dialog" aria-labelledby="modalTagihanLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Tagihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formTagihan">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <input type="number" class="form-control" name="tahun" id="tahun" placeholder="Contoh: 2025">
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah Tagihan (Rp)</label>
                            <input type="text" class="form-control" name="jumlah" id="jumlah" placeholder="Contoh: 500000">
                        </div>
                        <div class="form-group">
                            <label for="jatuh_tempo">Jatuh Tempo</label>
                            <input type="date" class="form-control" name="jatuh_tempo" id="jatuh_tempo">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" id="btnSebelumnya">
                            <i class="fe fe-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="submit" class="btn btn-success btn-sm">Simpan</button>
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

                        {{-- <div class="form-group">
                  <label for="edit_user_id" class="form-label">Nama</label>
                  <select class="form-control" name="user_id" id="edit_user_id">
                    <option value="">Pilih salah satu</option>
                  </select>    
                </div> --}}

                        <div class="form-group">
                            <label for="edit_name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_name" name="name"
                                placeholder="Nama">
                        </div>

                        <div class="form-group">
                            <label for="edit_nop" class="form-label">NOP</label>
                            <input type="text" class="form-control" id="edit_nop" name="nop"
                                placeholder="Masukkan Nomor Objek Pajak">
                        </div>

                        <div class="form-group">
                            <label for="edit_alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="3"
                                placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit_luas_bumi" class="form-label">Luas Bumi (m²)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_luas_bumi"
                                name="luas_bumi" placeholder="Masukkan luas bumi">
                        </div>

                        <div class="form-group">
                            <label for="edit_luas_bangunan" class="form-label">Luas Bangunan (m²)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_luas_bangunan"
                                name="luas_bangunan" placeholder="Masukkan luas bangunan">
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

    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Wajib Pajak: <span id="modalNamaUser"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tahun</th>
                                <th>Jumlah</th>
                                <th>Status Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detailTagihanBody">
                            <!-- Data akan diisi via JavaScript -->
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirmation">Konfirmasi</button>
                    </div>
                </div>
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

        $(document).ready(function() {
            table = $("#datatable").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('detail-pajak.datatable') }}",
                },
                columnDefs: [{
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        targets: 4,
                        render: function(data) {
                            return `${parseFloat(data).toFixed(2)} m²`;
                        }
                    },
                    {
                        targets: 5,
                        render: function(data) {
                            return `${parseFloat(data).toFixed(2)} m²`;
                        }
                    },
                    {
                        targets: 6,
                        render: function(data, type, full, meta) {
                            if (data === 'belum') {
                                return `<span class="badge badge-danger">Belum Dibayar</span>`;
                            } else if (data === 'dibayar') {
                                return `<span class="badge badge-success">Sudah Dibayarkan</span>`;
                            } else if (data === null) {
                                return `<span class="badge badge-secondary">Pajak Belum Di Tetapkan</span>`;
                            } else {
                                return `<span class="badge badge-primary">Tidak Di Ketahui</span>`;
                            }
                        }
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            const isDisabled = full.user_id === null;
                            return `
                                <button type="button" class="btn btn-info btn-sm ${isDisabled ? 'disabled' : ''}" onclick="handleDetail(${full.id}, ${isDisabled})">
                                    <i class="fe fe-eye"></i> Detail
                                </button>
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
                columns: [{
                        data: null
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'nop'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'luas_bumi'
                    },
                    {
                        data: 'luas_bangunan'
                    },
                    {
                        data: 'status_bayar'
                    },
                    // { data: null },
                    {
                        data: 'id'
                    }
                ],
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                }
            });
        });

        function handleDetail(id, isDisabled) {
            if (isDisabled) {
                toastr.warning("Pajak ini belum terealisasi");
            } else {
                lihatDetail(id);
            }
        }

        function lihatDetail(id) {
            $.ajax({
                url: `/super-admin/detail-pajak/tagihan/${id}`,
                type: 'GET',
                success: function(response) {
                    console.log(response.data[0].id); // cek id pertama

                    if (response.data.length > 0) {
                        $('#confirmation').attr('data-id', response.data[0].id);
                    }

                    if (response.status) {
                        $('#modalNamaUser').text(response.nama);

                        let rows = '';
                        response.data.forEach((tagihan, index) => {
                            rows += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${tagihan.tahun}</td>
                                    <td>Rp ${parseInt(tagihan.jumlah).toLocaleString('id-ID')}</td>
                                    <td>
                                        ${tagihan.status_bayar === 'dibayar'
                                            ? '<span class="badge badge-success">Sudah Dibayar</span>'
                                            : '<span class="badge badge-warning">Belum Dibayar</span>'}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="lihatDetailTagihan(${tagihan.id})">
                                            <i class="fa fa-eye"></i> Lihat Detail
                                        </button>
                                    </td>
                                </tr>`;
                        });

                        $('#detailTagihanBody').html(rows);
                        $('#modalDetail').modal('show');
                    }
                },
                error: function() {
                    toastr.error("Gagal mengambil data tagihan");
                }
            });
        }


        $('#confirmation').click(function() {
            let id = $(this).attr('data-id');

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Yakin ingin mengkonfirmasi status pembayaran ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Konfirmasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('detail-pajak.update-status') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#modalDetail').modal('hide');
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.status === 404 ?
                                    'Data tidak ditemukan.' : 'Gagal memperbarui data.'
                            });
                        }
                    });
                }
            });
        });

        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return prefix ? prefix + ' ' + rupiah : rupiah;
        }

        function editData(id) {
            console.log(id);

            $.ajax({
                url: "{{ url('super-admin/detail-pajak') }}/" + id,
                type: "GET",
                success: function(response) {
                    if (response.status === true) {
                        const data = response.data;
                        console.log(data);
                        let rows = '';

                        $('#edit_name').val(data.name);
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


        $('#jumlah').on('keyup', function () {
            let val = $(this).val();
            $(this).val(formatRupiah(val, 'Rp'));
        });

        $('#btnSebelumnya').on('click', function () {
            $('#modalTagihan').modal('hide'); 
            $('#modalTambahData').modal('show');
        });


        let dataWajibPajak = {};

        $('#simpanData').on('click', function () {
            // Ambil nilai dari for m
            const name = $('#name').val().trim();
            const nop = $('#nop').val().trim();
            const alamat = $('#alamat').val().trim();
            const luas_bumi = $('#luas_bumi').val().trim();
            const luas_bangunan = $('#luas_bangunan').val().trim();

            $('#tambahData input, #tambahData textarea').removeClass('is-invalid');

            if (name.length < 5) {
                $('#name').addClass('is-invalid');
                toastr.warning('Nama minimal 5 karakter.');
                return;
            }
            if (!/^\d{18}$/.test(nop)) {
                $('#nop').addClass('is-invalid');
                toastr.warning('NOP harus terdiri dari 18 digit angka.');
                return;
            }

            if (!alamat) {
                $('#alamat').addClass('is-invalid');
                toastr.warning('Alamat wajib diisi.');
                return;
            }

            if (!luas_bumi || isNaN(luas_bumi) || parseFloat(luas_bumi) <= 0) {
                $('#luas_bumi').addClass('is-invalid');
                toastr.warning('Luas bumi harus diisi dan bernilai positif.');
                return;
            }

            if (!luas_bangunan || isNaN(luas_bangunan) || parseFloat(luas_bangunan) <= 0) {
                $('#luas_bangunan').addClass('is-invalid');
                toastr.warning('Luas bangunan harus diisi.');
                return;
            }

            dataWajibPajak = {
                name,
                nop,
                alamat,
                luas_bumi,
                luas_bangunan,
            };

            $('#modalTambahData').modal('hide');
            $('#modalTagihan').modal('show');
        });


        $('#formTagihan').on('submit', function (e) {
            e.preventDefault();

            const tahun = $('#tahun').val().trim();
            const jumlahFormatted  = $('#jumlah').val().trim();
            const jatuh_tempo = $('#jatuh_tempo').val().trim();

            const jumlahCleaned = jumlahFormatted.replace(/[^0-9]/g, '');
            const jumlahAngka = parseFloat(jumlahCleaned);

            $('#formTagihan input').removeClass('is-invalid');

            const currentYear = new Date().getFullYear();
            if (!/^\d{4}$/.test(tahun) || tahun < 2000 || tahun > currentYear + 5) {
                $('#tahun').addClass('is-invalid');
                toastr.warning('Tahun harap diisi');
                return;
            }

            if (!jumlahCleaned || isNaN(jumlahAngka) || jumlahAngka <= 0) {
                $('#jumlah').addClass('is-invalid');
                toastr.warning('Jumlah tagihan harap diisi');
                return;
            }

            if (!jatuh_tempo) {
                $('#jatuh_tempo').addClass('is-invalid');
                toastr.warning('Tanggal jatuh harap diisi.');
                return;
            }

            const dataTagihan = {
                tahun,
                jumlah: jumlahAngka,
                jatuh_tempo
            };

            const dataGabungan = { ...dataWajibPajak, ...dataTagihan };

            $.ajax({
                url: "{{ url('super-admin/detail-pajak') }}",
                type: 'POST',
                data: dataGabungan,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === true) {
                        toastr.success(response.message || 'Data berhasil disimpan!');
                        $('#modalTagihan').modal('hide');
                        $('#tambahData')[0].reset();
                        $('#formTagihan')[0].reset();
                        table.ajax.reload();
                    } else {
                        toastr.warning(response.message || 'Data tidak berhasil diproses.', 'Peringatan');
                    }
                },
                error: function () {
                    toastr.error('Gagal menyimpan data');
                }
            });
        });

        $("#updateData").on("click", function(e) {
            e.preventDefault();

            let id = $("#editData").data('id');

            let formData = {
                name: $("#edit_name").val(),
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
                    let message = xhr.responseJSON?.message || xhr.responseText ||
                        'Terjadi kesalahan saat mengupdate data.';
                    toastr.error(message, 'Gagal');
                }
            });
        });
    </script>
@endsection
