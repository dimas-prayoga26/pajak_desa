@extends('dashboard.layout.app')

@section('title', 'Detail Pajak')

@section('css')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@section('breadcumb')
    <div class="page-header">
        <h1 class="my-auto page-title">Detail History</h1>
        <div>
            <ol class="mb-0 breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Detail History
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
                    <h1>Detail History</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Detail History</li>
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

                        <div class="col-md-6 d-flex align-items-center gap-3">
                            <h3 class="card-title mb-0">Data Detail History</h3>


                            <div class="form-group mb-0 ml-3">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="daterange" placeholder="Pilih rentang tanggal">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="datatable" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Nama</th>
                                <th>Nop</th>
                                <th>Tahun</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        flatpickr("#daterange", {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d M Y",
            allowInput: true,
            defaultDate: [
                new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                new Date()
            ],
            onClose: function(selectedDates, dateStr, instance) {
                table.ajax.reload();
            }
        });



        var table;

        $(document).ready(function() {
            table = $("#datatable").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('detail-tagihan.datatable') }}",
                    data: function (d) {
                        d.daterange = $('#daterange').val();
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        targets: 6,
                        render: function(data, type, full, meta) {
                            if (type === 'display' || type === 'filter') {
                                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
                            }
                            return data;
                        }
                    }
                ],

                columns: [
                    { data: null },
                    { data: 'tanggal' },
                    { data: 'waktu' },
                    { data: 'nama' },
                    { data: 'nop' },
                    { data: 'tahun' },
                    { data: 'total' }
                ],
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                }
            });
        });

    </script>
@endsection
