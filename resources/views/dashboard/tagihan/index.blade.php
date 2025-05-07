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
                        <div class="col-md-9 d-flex align-items-center gap-2">
                            <h3 class="card-title mb-0 mr-2">Data Detail Tagihan</h3>
                        </div>
                        <div class="col-md-3 text-right">
                            <input type="text" id="searchNop" class="form-control form-control-sm" placeholder="Cari berdasarkan NOP">
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>nama</th>
                                <th>tahun</th>
                                <th>jumlah</th>
                                <th>Status Bayar</th>
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
        var table;

        $(document).ready(function () {
            table = $("#datatable").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false, // Disable search bawaan DataTables
                ajax: {
                    url: "{{ route('detail-tagihan.datatable') }}",
                    data: function (d) {
                        d.nop = $('#searchNop').val(); // Ambil nilai dari input manual
                    }
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
                            return full.wajib_pajak?.user?.biodata?.nama || '-';
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
                ],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: null },
                    { data: 'tahun' },
                    { data: 'jumlah' },
                    { data: 'status_bayar' },
                    { data: 'id' }
                ],
                language: {
                    searchPlaceholder: 'Cari NOP',
                    sSearch: ''
                }
            });

            // Reload datatable saat user mengetik NOP
            $('#searchNop').on('keyup', function () {
                table.ajax.reload();
            });
        });

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


        function unformatRupiah(str) {
            return str.replace(/[^,\d]/g, '').replace(',', '.');
        }

        $('#updateData').on('click', function(e) {
            e.preventDefault();
            
            let id = $("#editData").data('id');
            let jumlah = $('#edit_jumlah_tagihan').val();
            let cleanedJumlah = unformatRupiah(jumlah); // Hapus Rp dan titik

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
                    table.ajax.reload();
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                    toastr.error(msg);
                }
            });
        });





    </script>
@endsection
