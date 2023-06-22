@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Order Import')
@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
    #content-card,
    #content-body {
        all: unset;
    }

    .import-card {
        margin: auto !important;
    }

    .dt-upload {
        border: 1px dashed;
        display: flex;
        justify-content: space-between;
        padding: 8px 15px 8px 20px;
        background: #eee;
        border-radius: 5px !important;
    }

    ul#file-upload-error {
        color: red;
    }

    .upload_style {
        width: auto;
        margin-top: 6px;
    }

    .excel_template {
        display: inline-block;
        color: #868aa8;
        background: #eee;
        border-radius: 4px !important;
        margin-top: 40px;
        cursor: pointer;
        padding: 7px 15px;
        text-align: center;
    }

    .excel_template a {
        text-decoration: none;
    }

    .excel_template a span {
        font-size: 13px;
        font-weight: 500;
        line-height: 13px;
        letter-spacing: normal;
        text-align: center;
        color: #343a40;
    }

    .excel_template a i {
        color: #343a40;
    }

    .error-message {
        margin-top: 5px;
        color: red;
        font-size: 14px;
    }

    #backToJobDetail {
        width: 200px !important;
        border-radius: 4px !important;
    }
</style>
@endsection
@section('content')
<div class="card card-container import-card w-50">
    <div class="card-body">
        <div>
            <div class="m-login__logo">
                <h3 class="text-center"><b> IMPORT ORDERS</b></h3>
            </div>
            <div class="excel_template">
                <a href="/sample/order_sample.xlsx" id="export" onclick="exportOrderCsv(event.target);">
                    <i class="fa-regular fa-file-excel"></i>&nbsp;<span>Click Here to Download Template</span>
                </a>
            </div>
            <div class="upload_style">
                <div class="m-login__head">
                    <b>
                        <p></p>
                    </b>
                </div>
                <div id="resumable-error" style="display: none">
                    Resumable not supported
                </div>
                <input type="hidden" name="import_type" id="importTypeInput">
                <div id="resumable-drop" class="dt-upload" style="display: none">
                    <a id="resumable-browse" data-url="{{url('/import-orders')}}"></a>
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <ul id="file-upload-list" class="list-unstyled" style="display: none; font-size: 14px; color:#9d9393"></ul>
                <ul id="file-upload-error" class="list-unstyled" style="display: none"></ul>
                <div class="progress dt-progress" style="display: none; margin-top: 15px; margin-bottom: 10px; height: 12px; border-radius: 10px !important;">
                    <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span class="sr-only">70% Complete</span>
                    </div>
                </div>
                @if (\Session::has('message'))
                <div class="alert @if (\Session::has('alert-type')) {{ \Session::get('alert-type') }} @endif">
                    <ul class="list-unstyled">
                        <li>{{ \Session::get('message') }}</li>
                    </ul>
                </div>
                @endif
            </div>
            <div class="error-message">
                <p> <span>* </span>Please do not close this tab while uploading files</p>
            </div>
            <button class="btn btn-dark" id="backToJobDetail">Reset</button>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
<script>
    var import_type = 'orders';
    $("#importBtn").hide();
    $("#zipImportWizard").show();
    $("#importTypeInput").val(importType);
    $(".m-login__head p").html('Import by uploading the prepared EXCEL');
    $("#resumable-browse").html('Upload Excel');

    $("#backToJobDetail").on('click', function() {
        location.reload();
    });

    var $ = window.$; // use the global jQuery instance

    var $fileUpload = $('#resumable-browse');
    var $fileUploadDrop = $('#resumable-drop');
    var $uploadList = $("#file-upload-list");
    var $uploadError = $("#file-upload-error");
    var folderNameWithTime = new Date().getTime();

    if ($fileUpload.length > 0 && $fileUploadDrop.length > 0) {

        var fileNumber = '';
        var importType = '';

        var resumable = new Resumable({
            // Use chunk size that is smaller than your maximum limit due a resumable issue
            // https://github.com/23/resumable.js/issues/51
            chunkSize: 1 * 1024 * 1024, // 1MB
            simultaneousUploads: 1,
            testChunks: false,
            throttleProgressCallbacks: 1,
            fileType: ['csv', 'xlsx'],
            // Get the url from data-url tag
            target: $fileUpload.data('url'),
            resumableTotalSize: 4 * 1024 * 1024,
            // Append token to the request - required for web routes
            query: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                file_number: fileNumber,
                folder_name: folderNameWithTime
            }
        });

        // Resumable.js isn't supported, fall back on a different method
        if (!resumable.support) {
            $('#resumable-error').show();
        } else {
            // Show a place for dropping/selecting files
            $fileUploadDrop.show();
            resumable.assignDrop($fileUpload[0]);
            resumable.assignBrowse($fileUploadDrop[0]);

            // Handle file add event
            resumable.on('fileAdded', function(file) {
                fileNumber = file.fileNumber;
                importType = $("#importTypeInput").val();

                $(".progress").show();
                // Show progress pabr
                $uploadList.show();
                // Show pause, hide resume
                $('.resumable-progress .progress-resume-link').hide();
                $('.resumable-progress .progress-pause-link').show();
                // Add the file to the list
                $uploadList.append('<li class="resumable-file-' + file.uniqueIdentifier +
                    '">Uploading <span class="resumable-file-name"></span> <span class="resumable-file-progress"></span>');
                $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-name').html(file.fileName);
                // Actually start the upload
                resumable.upload();
            });
            resumable.on('fileSuccess', function(file, message) {
                // Reflect that the file upload has completed                    
                var res = JSON.parse(message);
                console.log(res);
                if (res.showFinalResponse) {
                    $("#finalMessage").show();
                }
                if (res.status === false) {
                    $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(0%)');
                    showToast(res.message);

                    $('.progress-bar').css({
                        width: '0%'
                    });

                    $uploadList.hide();
                    $uploadError.show();
                    $uploadError.append(res.message);

                } else {
                    $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(completed)');
                    showToast("File uploaded successfully.")
                    $('.progress-bar').css({
                        backgroundColor: '#02ad18'
                    });
                    $('.error-message').hide();
                }

            });
            resumable.on('fileError', function(file, message) {
                // Reflect that the file upload has resulted in error
                $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html(
                    '(file could not be uploaded: ' + message + ')');
            });
            resumable.on('fileProgress', function(file) {
                // Handle progress for both the file and the overall upload
                $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html(Math.floor(file
                    .progress() * 100) + '%');

                $('.progress-bar').css({
                    width: Math.floor(resumable.progress() * 100) + '%'
                });
            });
        }

    }

    function exportOrderCsv(_this) {
        let _url = $(_this).data('href');
        window.location.href = _url;
    }

    function showToast(message, type) {
        Toastify({
            text: message,
            gravity: "top",
            position: "center",
            style: {
                background: type == 'error' ? "red" : "#02ad18",
            },
            duration: 3000,
        }).showToast();
    }
</script>
@stop