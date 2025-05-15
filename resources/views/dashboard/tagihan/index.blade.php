@extends('dashboard.layout.app')

@section('title', 'Detail Tagihan')

@section('css')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @role('superAdmin')
                        <h1 class="my-auto page-title">Detail Tagihan</h1>
                    @else
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <h1 class="page-title mb-0" style="font-size: 1.25rem;">Lihat Detail Tagihan NOP:</h1>
                            <div style="min-width: 300px;">
                                <select class="form-control" name="selectNop" id="selectNop">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    @endrole

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Detail Tagihan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="row w-100 align-items-center">

                        <div class="col-md-6 d-flex align-items-center gap-2">
                            <h3 class="card-title mb-0 mr-2">Data Detail Tagihan</h3>
                        </div>

                        @role('superAdmin')

                        <div class="col-md-6 d-flex justify-content-end align-items-center gap-2">
                            <input type="text" id="searchNop" class="form-control form-control-sm w-auto mr-2" placeholder="Cari berdasarkan NOP">

                            <button
                                type="button"
                                class="btn btn-primary btn-sm ml-2"
                                id="btnTambahTagihan"
                                data-toggle="modal"
                                data-target="#modalTambahTagihan"
                            >
                                <i class="fe fe-plus"></i> Tambah Tagihan
                            </button>

                        </div>
                        @endrole
                    </div>
                </div>

                <div class="card-body">
                    <table id="datatable" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No.</th>
                                @role('superAdmin')
                                    <th>nama</th>
                                @endrole
                                <th>tahun</th>
                                <th>jumlah</th>
                                <th>Status Bayar</th>
                                @role('superAdmin')
                                    <th>Aksi</th>
                                @else
                                    <th>Informasi</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahTagihan" tabindex="-1" role="dialog" aria-labelledby="modalLabelTagihan" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formTambahTagihan">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelTagihan">Tambah Tagihan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formTagihan">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="nop" class="form-label">Nop</label>
                                <select class="form-control" name="nop" id="nop">
                                    <option value="">Cari NOP</option>
                                </select>
                            </div>

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
                            <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                            <button type="button" class="btn btn-primary btn-sm" id="simpanData">
                                <i class="fe fe-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </form>
        </div>
        </div>


    <div class="modal fade" id="modalEditData" tabindex="-1" aria-labelledby="modalEditDataLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="editData">
              <div class="modal-header">
                <h5 class="modal-title" id="modalEditDataLabel">Edit Jumlah Tagihan</h5>
                <button type="button" class="close" data-dismiss="modal">
                  <span>&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="edit_jumlah_tagihan">Jumlah Tagihan (Rp)</label>
                  <input type="text" id="edit_jumlah_tagihan" name="edit_jumlah_tagihan" class="form-control" placeholder="Masukkan jumlah tagihan" required>
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

        $('#selectNop').select2({
            placeholder: 'Cari NOP...',
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ route('detail-pajak.nop-options') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#modalTambahTagihan').on('shown.bs.modal', function () {
            $('#nop').select2({
                placeholder: 'Cari NOP...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modalTambahTagihan'),
                ajax: {
                    url: "{{ route('detail-pajak.nop-options') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        });

        var table;

        let debounceTimer;
        let columns;
        let columnDefs = [];

        const userRole = @json(Auth::user()->getRoleNames()->first());

        if (userRole === 'superAdmin') {
            columns = [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: null },
                { data: 'tahun' },
                { data: 'jumlah' },
                { data: 'status_bayar' },
                { data: 'id' }
            ];

            columnDefs = [
                {
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    targets: 1,
                    render: function (data, type, full, meta) {
                        if (!full.wajib_pajak?.user_id || !full.wajib_pajak?.user) {
                            return '<span class="text-danger">Belum terhubung dengan pengguna</span>';
                        }

                        return full.wajib_pajak.user.biodata?.nama || '-';
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, full, meta) {
                        let jumlah = parseFloat(data);
                        if (!isNaN(jumlah)) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(jumlah);
                        }
                        return '-';
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, full, meta) {
                        if (data === 'dibayar') {
                            return '<span class="badge badge-success">Sudah Dibayar</span>';
                        } else if (data === 'belum') {
                            return '<span class="badge badge-warning">Belum Dibayar</span>';
                        } else {
                            return '<span class="badge badge-secondary">Tidak Diketahui</span>';
                        }
                    }
                },
                {
                    targets: 5,
                    render: function (data, type, full, meta) {
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
            ];
        } else if (userRole === 'warga') {
            columns = [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'tahun' },
                { data: 'jumlah' },
                { data: 'status_bayar' },
                { data: 'id' }
            ];

            columnDefs = [
                {
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, full, meta) {
                        let jumlah = parseFloat(data);
                        if (!isNaN(jumlah)) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(jumlah);
                        }
                        return '-';
                    }
                },
                {
                    targets: 3,
                    render: function (data, type, full, meta) {
                        if (data == 'dibayar' || data == 'dikonfirmasi') {
                            return '<span class="badge badge-success">Sudah Dibayar</span>';
                        } else if (data === 'belum') {
                            return '<span class="badge badge-warning">Belum Dibayar</span>';
                        } else {
                            return '<span class="badge badge-secondary">Tidak Diketahui</span>';
                        }
                    }
                },
                {
                    targets: 4,
                    render: function (data, type, full, meta) {
                        let tombol = '';

                        switch (full.status_bayar) {
                            case 'belum':
                                tombol = `
                                    <button type="button" class="btn btn-success btn-sm" onclick="bayarTagihan(${full.id})">
                                        <i class="fe fe-dollar-sign"></i> Bayar
                                    </button>
                                `;
                                break;

                            case 'dibayar':
                                tombol = `
                                    <span class="badge badge-success">Pembayaran Sudah Dikonfirmasi</span>
                                `;
                                break;

                            case 'dikonfirmasi':
                                tombol = `
                                    <span class="badge badge-success">Pembayaran Sudah Dikonfirmasi</span>
                                `;
                                break;

                            default:
                                tombol = `<span class="badge badge-secondary">Status Tidak Diketahui</span>`;
                        }

                        return tombol;
                    }
                }

            ];
        }

        $(document).ready(function () {
            table = $("#datatable").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false,
                ajax: {
                    url: "{{ route('detail-tagihan.datatable') }}",
                    data: function (d) {
                        const searchNop = $('#searchNop').val();
                        const selectNop = $('#selectNop').val();

                        d.nop = searchNop || selectNop;
                    },
                    dataSrc: function (json) {
                        const validTypes = ['success', 'error', 'warning', 'info'];
                        const type = validTypes.includes(json.type) ? json.type : 'info';

                        if (json.status === false && json.message) {
                            toastr[type](json.message);
                        }

                        return json.data;
                    },
                },
                columnDefs: columnDefs,

                columns: columns,
                language: {
                    searchPlaceholder: 'Cari NOP',
                    sSearch: '',
                    zeroRecords: "Tidak ditemukan hasil",
                }
            });

            $('#searchNop').on('keyup', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    table.ajax.reload();
                }, 500);
            });

            $('#selectNop').on('change', function () {
                table.ajax.reload();
            });
        });

        function bayarTagihan(tagihanId) {

            const url = "{{ url('pajak-tagihan/bayar') }}/" + tagihanId;

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.snap_token) {
                        snap.pay(response.snap_token, {
                            onSuccess: function (result) {
                                // console.log('Pembayaran berhasil:', result);
                                toastr.success('Pembayaran berhasil.');
                                $('#datatable').DataTable().ajax.reload();
                            },
                            onPending: function (result) {
                                // console.log('Menunggu pembayaran:', result);
                                toastr.info('Menunggu penyelesaian pembayaran.');
                                $('#datatable').DataTable().ajax.reload();
                            },
                            onError: function (result) {
                                console.error('Pembayaran gagal:', result);
                                toastr.error('Pembayaran gagal.');
                            },
                            onClose: function () {
                                toastr.info('Kamu menutup popup pembayaran.');
                            }
                        });
                    } else {
                        toastr.error('Gagal memulai pembayaran.');
                    }
                },
                error: function (xhr) {
                    toastr.error('Terjadi kesalahan saat memproses pembayaran.');
                }
            });
        }


        function formatRupiah(angka, prefix = 'Rp') {
            let number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix + ' ' + rupiah;
        }

        $('#edit_jumlah_tagihan').on('input', function () {
            let val = $(this).val();
            $(this).val(formatRupiah(val));
        });



        function editData(id) {
            $.ajax({
                url: "{{ url('super-admin/detail-tagihan') }}/" + id,
                type: "GET",
                success: function(response) {
                    if (response.status === true) {
                        const data = response.data;

                        $('#edit_jumlah_tagihan').val(formatRupiah(data.jumlah));
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
                        url: "{{ url('super-admin/detail-tagihan') }}/" + id,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === true) {
                                toastr.success(response.message, 'Berhasil');
                                $('#datatable').DataTable().ajax.reload();
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


        function unformatRupiah(str) {
            return str.replace(/[^,\d]/g, '').replace(',', '.');
        }

        $('#updateData').on('click', function(e) {
            e.preventDefault();

            let id = $("#editData").data('id');
            let jumlah = $('#edit_jumlah_tagihan').val();
            let cleanedJumlah = unformatRupiah(jumlah);

            $.ajax({
                url: `/super-admin/detail-tagihan/${id}`,
                type: 'PUT',
                data: { jumlah: cleanedJumlah },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success(response.message || 'Tagihan berhasil diperbarui');
                    $('#modalEditData').modal('hide');
                    $('#editData')[0].reset();
                    $('#datatable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                    toastr.error(msg);
                }
            });
        });

        $('#jumlah').on('keyup', function () {
            let val = $(this).val();
            $(this).val(formatRupiah(val, 'Rp'));
        });

        $("#simpanData").on("click", function (e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append("wajib_pajak_id", $("#nop").val());
            formData.append("tahun", $("#tahun").val());
            formData.append("jumlah", $("#jumlah").val());
            formData.append("jatuh_tempo", $("#jatuh_tempo").val());

            $.ajax({
                url: "{{ url('super-admin/detail-tagihan') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === true) {
                        toastr.success(response.message, 'Berhasil');
                        $("#formTagihan")[0].reset();
                        $('#modalTambahTagihan').modal('hide');
                        $('#datatable').DataTable().ajax.reload();
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





    </script>
@endsection
