@extends('admin.datatable')
@section('content')
<style>
    :root {
        --bg-color: #f8fafc;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --btn-color: #25499c;
        --btn-background-color: #25499C3D;
        --primary-color: #5179d6;
        --primary-light-color: #e7edfb;
        --indigo-primary: #4f46e5;
        --indigo-hover: #4338ca;
        --indigo-light: #eef2ff;
        --border-color: #e2e8f0;
        --white: #ffffff;
        --emerald: #10b981;
        --emerald-light: #ecfdf5;
        --rose: #f43f5e;
        --mellow-red: #af1e1e;
        --cosmos: #f8d4d4;
        --linen: #fbeaea;
        --rose-light: #fff1f2;
        --amber: #f59e0b;
        --amber-light: #fffbeb;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --radius-lg: 0.75rem;
        --radius-xl: 1rem;
        --radius-2xl: 1.5rem;
        --radius-3xl: 2rem;
    }

    /* Template Choice Chips */
    .tab-selector {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    @media (min-width: 640px) {
        .tab-selector {
            flex-direction: row;
        }
    }

    .tab-chip {
        flex: 1;
        cursor: pointer;
    }

    .chip-content {
        padding: 1.5rem;
        border-radius: 2rem;
        border: 2px solid var(--border-color);
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
        transition: all 0.3s;
        box-shadow: var(--shadow-sm);
        opacity: 0.7;
    }

    .tab-chip:hover .chip-content {
        opacity: 1;
    }

    .tab-chip.active .chip-content {
        background: var(--white);
        /* border-color: var(--primary-color); */
        box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.1);
        opacity: 1;
        transform: scale(1.02);
    }

    .chip-icon {
        padding: .5rem;
        border-radius: 0.5rem;
        background: #e2e8f0;
        color: var(--text-secondary);
        transition: all 0.3s;
    }

    .small-icon svg {
        width: 28px;
        height: 28px;
    }

    .tab-chip.active .chip-icon {
        background: var(--primary-light-color);
        color: var(--primary-color);
    }

    .chip-info h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .tab-chip.active .chip-info h3 {
        color: var(--text-primary);
    }

    .chip-info p {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.25rem;
    }

    .chip-status {
        font-size: 0.625rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
    }

    .chip-status.active {
        background: var(--btn-background-color);
        color: var(--btn-color);
    }

    .chip-status.inactive {
        color: var(--text-muted);
    }

    .table-responsive table td {
        font-size: 0.75rem;
    }

    .template-view {
        display: none;
        background: var(--white);
        border-radius: 1rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        padding: 10px;
    }

    .template-view.active {
        display: block;
    }
</style>
<main class="container main-layout">
    <div style="height: 20px"></div>
    <div class="tab-selector">
        <div class="tab-chip" id="tab-db">
            <div class="chip-content">
                <div style="display: flex; flex-direction:row; align-items: center; gap:8px;">
                    <div class="chip-icon small-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div style="display: flex; align-items: center; justify-content:center;" class="chip-info">
                        <h3 style="margin: 0px">Database Backups</h3>
                    </div>
                </div>
                <button class="btn btn-primary" id="create-db-backup">
                    <span class="button-loader" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span class="chip-status">Take Fresh Database Backup</span>
                </button>
            </div>
        </div>
        <div class="tab-chip" id="tab-file">
            <div class="chip-content">
                <div style="display: flex; flex-direction:row; align-items: center; gap:8px;">
                    <div class="chip-icon small-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div style="display: flex; align-items: center; justify-content:center;" class="chip-info">
                        <h3 style="margin: 0px">File Backups</h3>
                    </div>
                </div>
                <button class="btn btn-primary" id="create-file-backup">
                    <span class="button-loader" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span class="chip-status">Take Fresh File Backup</span>
                </button>

            </div>
        </div>
    </div>
    <div class="grid-container">
        <div class="template-view active" id="content-db">
            <div class="view-content">
                <div class="table-responsive">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th style="width: 5%; font-size:0.75rem;">#</th>
                                <th class="text-center" style="width:35%;font-size:0.75rem;text-transform:capitalize">File Name</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Size</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Date</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Time</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Admin</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($databaseBackups as $key => $databaseBackup)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $databaseBackup->filename }}</td>
                                    <td class="text-center">{{ round($databaseBackup->file_size/1024/1024, 2).'MB' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($databaseBackup->started_at)->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($databaseBackup->started_at)->format('h:i A') }}</td>
                                    <td class="text-center">{{ $databaseBackup->admin->name }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-alt-secondary" href="{{route('admin.systemManagement.backup.download', ['id' => $databaseBackup->id])}}" data-toggle="tooltip" title="Download">
                                            <i class="fa fa-download" style="font-size:10px;"></i>
                                        </a>
                                        <a class="btn btn-sm btn-alt-danger" onclick="onClickDelete({{$databaseBackup->id}})" data-toggle="tooltip" title="Delete">
                                            <i class="fa fa-fw fa-times text-danger" style="font-size:10px;"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="template-view" id="content-file">
            <div class="view-content">
                <div class="table-responsive">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th style="width: 5%; font-size:0.75rem;">#</th>
                                <th class="text-center" style="width:35%;font-size:0.75rem;text-transform:capitalize">File Name</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Size</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Date</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Time</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Admin</th>
                                <th class="text-center" style="width:12%;font-size:0.75rem;text-transform:capitalize">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fileBackups as $key => $fileBackup)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $fileBackup->filename }}</td>
                                    <td class="text-center">{{ round($fileBackup->file_size/1024/1024, 2).'MB' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($fileBackup->started_at)->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($fileBackup->started_at)->format('h:i A') }}</td>
                                    <td class="text-center">{{ $fileBackup->admin->name }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-alt-secondary" href="{{route('admin.systemManagement.backup.download', ['id' => $fileBackup->id])}}" data-toggle="tooltip" title="Download">
                                            <i class="fa fa-download" style="font-size:10px;"></i>
                                        </a>
                                        <a class="btn btn-sm btn-alt-danger" onclick="onClickDelete({{$fileBackup->id}})" data-toggle="tooltip" title="Delete">
                                            <i class="fa fa-fw fa-times text-danger" style="font-size:10px;"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 20px"></div>
</main>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {
        var hash = window.location.hash;
        if (hash == '#file') {
            onClickTabFile();
        } else {
            onClickTabDB();
        }
    });

    $('#tab-db').on('click', function(e) {
        window.location.hash = 'db';
        onClickTabDB();
    });

    $('#tab-file').on('click', function(e) {
        window.location.hash = 'file';
        onClickTabFile();
    });

    function onClickTabDB() {
        $("#tab-file").removeClass('active');
        $("#tab-db").addClass('active');
        $("#create-db-backup").prop('disabled', false);
        $("#create-file-backup").prop('disabled', true);
        $("#content-file").removeClass('active');
        $("#content-db").addClass('active');
    }

    function onClickTabFile() {
        $("#tab-db").removeClass('active');
        $("#tab-file").addClass('active');
        $("#create-file-backup").prop('disabled', false);
        $("#create-db-backup").prop('disabled', true);
        $("#content-db").removeClass('active');
        $("#content-file").addClass('active');
    }

    function onClickDelete(id) {
        Swal.fire({
            title: 'Delete Backup File?',
            text: 'This backup file will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {
                deleteFile(id);
            }

        });
    }

    function deleteFile(id) {
        $.ajax({
            url: "{{route('admin.systemManagement.backup.delete')}}",
            type: 'POST',
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    // alert('success')
                } else {
                    alert('failed')
                }
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function() {
                console.log(error)
            },
            complete: function() {

            }
        });
    }

    $('#create-db-backup').on('click', function(e) {
        e.stopPropagation();
        $(this).prop('disabled', true);
        $(this).find('.button-loader').show();
        $.ajax({
            url: "{{route('admin.systemManagement.databaseBackup')}}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    // alert('success')
                    $('#create-db-backup').find('.button-loader').hide();
                } else {
                    alert('failed')
                    $('#create-db-backup').find('.button-loader').hide();
                }
                setTimeout(function() {
                    window.location.reload();
                }, 300);
            },
            error: function(error) {
                console.log(error)
                $('#create-db-backup').find('.button-loader').hide();
            },
            complete: function() {

            }
        });

    });

    $('#create-file-backup').on('click', function(e) {
        e.stopPropagation();
        $("#create-file-backup").prop('disabled', true);
        $(this).find('.button-loader').show();
        $.ajax({
            url: "{{route('admin.systemManagement.filesBackup')}}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    // alert('success')
                    $('#create-file-backup').find('.button-loader').hide();
                } else {
                    alert('failed')
                    $('#create-file-backup').find('.button-loader').hide();
                }
                setTimeout(function() {
                    window.location.reload();
                }, 300);
            },
            error: function(error) {
                console.log(error)
                $('#create-file-backup').find('.button-loader').hide();
            },
            complete: function() {

            }
        });
    });
</script>
@endsection
