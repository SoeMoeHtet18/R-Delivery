@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Shop Listing')
@section('content')
<style>
    .pdf-ul {
        padding-left: 0;
        padding-right: 0;
        border-radius: 3px;
        display: flex;
        list-style-type: none;
        padding-left: 0;
        margin-left: auto;
        margin-bottom: 0;
    }

    .pdf-ul li {
        background: #f4f5f8;
        margin: 0;
    }

    .pdf-ul li a {
        padding: 0;
        padding-right: 6px;
        padding-left: 2px;
        font-size: 13px;
        text-decoration: none;
    }

    .pdf-ul li a:first-child {
        border-right: 1px solid #dfe2ea;
    }
</style>

<div class="d-flex justify-content-between mb-3">
    <div class="create-button">
        <a href="{{route('shops.create')}}" class="btn create-btn">Add Shop</a>
    </div>

    <button class="btn btn-link" id="toggleFilter">
        <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.87768 20C7.55976 20 7.29308 19.88 7.07765 19.64C6.86222 19.4 6.75487 19.1033 6.75562 18.75V11.25L0.247706 2C-0.0328074 1.58333 -0.0750713 1.14583 0.120914 0.6875C0.3169 0.229167 0.658378 0 1.14535 0H16.8541C17.3403 0 17.6818 0.229167 17.8785 0.6875C18.0753 1.14583 18.033 1.58333 17.7518 2L11.2438 11.25V18.75C11.2438 19.1042 11.1361 19.4012 10.9207 19.6412C10.7053 19.8812 10.439 20.0008 10.1218 20H7.87768Z" fill="black" />
        </svg>
    </button>
</div>

<div class="card m-3 mt-0 filter-content" style="display: none;">
    <div class="row tdFilter">
        <div class="col-md-12 col-sm-12 m-3">
            <h2>Filter</h2>
        </div>
    </div>
    <div class="row">
        <div class="filter-box">
            <div class="mb-3 p-3 col-4">
                <label for="search">
                    <strong>Search</strong>
                </label>
                <div class="col-10">
                    <input type="text" id="search" name="search" class="form-control" />
                </div>
            </div>
            <div class="mb-3 p-3 col-4">
                <label for="date_range">
                    <strong>Total Ways By Date Range</strong>
                </label>
                <div class="col-10">
                    <div style="position: relative;">
                        <input type="text" name="datefilter" value="" class="form-control"/>
                        <span class="fa fa-calendar" style="position: absolute; top: 12px; right: 8px;"></span>
                    </div>
                    <input type="hidden" name="start_date" id="start_date">
                    <input type="hidden" name="end_date" id="end_date">
                </div>
            </div>
        </div>

    </div>
    <div class="d-flex flex-row-reverse pb-3">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 btncenter margin-btn filter-footer">
            <button class="btn btn-primary search_filter">Filter</button>

            <button class="btn btn-secondary" id="reset">Reset</button>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end pb-3">
    <div class="d-inline-block">
        <ul class="pdf-ul">
            <li>
                <form id="pdf_form" method="GET" style="display: inline;">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <button type="submit" id="pdf_button" class="btn border">
                        <i class="fa-regular fa-file-pdf"></i>&nbsp;<span>PDF</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">Shop Lists</div>
    </div>
    <div class="portlet-body">
        <table id="datatable" class="table table-striped table-hover table-responsive datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Township Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Total Ways</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            </tbody>
        </table>
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        $("#toggleFilter").on("click", function() {
            $(".filter-content").slideToggle(300);
        });

        function formatWithNumberingSystem(number) {
            const formattedNumber = parseFloat(number).toFixed(0); 
            return formattedNumber.replace(/\d(?=(\d{3})+$)/g, '$&,');
        }
        
        get_ajax_dynamic_data(search = '', start = '', end = '');

        function get_ajax_dynamic_data(search,start,end) {
            var table = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                buttons: [
                    {
                        extend: 'pdf',
                        title: 'Shops List',
                        filename: 'Shops List',
                        pageSize: 'LEGAL',
                        charset: 'UTF-8',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // Exclude column index 6 (Action)
                        },
                        customize: function (doc) {
                            // Center-align the table header and data cells
                            var table = doc.content[1].table;
                            table.body.forEach(function (row) {
                                row.forEach(function (cell) {
                                    cell.alignment = 'start';
                                });
                            });

                            // Add background color to header row
                            table.headerRows = 1;
                            table.widths = Array(table.body[0].length + 1).join('*').split('');
                            table.body[0].forEach(function (headerCell) {
                                headerCell.fillColor = '#CCCCCC';
                            });
                        }
                    },
                ],
                ajax: {
                    "url": '/ajax-get-shops-data',
                    "type": "GET",
                    "data": function(r) {
                        r.search = search;
                        r.from_date = start;
                        r.to_date = end;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'township_name',
                        name: 'township_name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'total_ways',
                        name: 'total_ways'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {
                        "render": function(data, type, row) {
                            return '<a href="/shops/' + row.id + '">' + row.name + '</a>';
                        },
                        "targets": 1
                    },
                    {
                        "render": function(data, type, row) {
                            if(row.township_id != null){
                                return '<a href="/townships/' + row.township_id + '">' + row.township_name + '</a>';
                            }else{
                                return '';
                            }
                            
                        },
                        "targets": 2
                    },
                    {
                        "render": function(data, type, row) {
                            return formatWithNumberingSystem(row.total_ways);
                        },
                        "targets": 5
                    },
                ]
            });
            $('.search_filter').click(function() {
                var search = $('#search').val();
                var start = $('#start_date').val();
                var end = $('#end_date').val();
                table.destroy();
                get_ajax_dynamic_data(search,start,end);
            });
            $("#reset").click(function() {
                $("#search").val("").trigger("change");
                $("#start_date").val("").trigger("change");
                $("#end_date").val("").trigger("change");
                $('input[name="datefilter"]').val("");
                var start = $("#start_date").val();
                var end = $('#end_date').val();
                var search = $('#search').val();
                table.destroy();
                get_ajax_dynamic_data(search,start,end);
            });
            // $("#pdf_button").on("click", function() {
            //     table.button( '.buttons-pdf' ).trigger();
            // })
            
        }

        // for date range picker
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $(".fa-calendar").on("click", function() {
            $('input[name="datefilter"]').trigger("click");
        });

        const form = $('#pdf_form');
        form.submit(function(event) {
            // Prevent the default form submission behavior
            event.preventDefault();
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            console.log('download shops list pdf');
            
            generatePDF(start, end);
        });

        function generatePDF(start, end) {
            // Create the download URL with query parameters
            const downloadUrl = `/generate-shops-list-pdf?from_date=${encodeURIComponent(start)}&to_date=${encodeURIComponent(end)}`;
            // Navigate to the download URL
            window.location.href = downloadUrl;
        }
    });
</script>
@endsection