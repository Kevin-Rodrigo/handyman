@extends('adminmodule::layouts.master')

@section('title',translate('database_backup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="page-title">{{translate('database_backup')}}</h2>
                <div>
                    <a class="btn btn--primary" onclick="database_backup_modification('{{route('admin.business-settings.backup-database-backup')}}', '{{translate('Want to take new backup').'?'}}')">
                        <span class="material-icons">backup</span> {{translate('Take a new backup')}}
                    </a>
                </div>
            </div>

            <div class="card card-body mb-4">
                <h3 class="mb-2">{{translate('Instructions')}} : </h3>
                <p> 1. {{translate('If you are not using any Linux server, you must have to update the value of DUMP_BINARY_PATH from the .env file.')}}</p>
            </div>

            <div class="d-flex flex-wrap justify-content-end align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                <div class="d-flex gap-2 fw-medium mb-1">
                    <span class="opacity-75">{{translate('Total_Backup_Databases')}}:</span>
                    <span class="title-color">{{count($filenames)}}</span>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-tab-pane">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th class="text-center">{{translate('File_Name')}}</th>
                                        <th class="text-center">{{translate('action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($filenames as $key=>$file_name)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td class="text-center">{{$file_name}}</td>
                                            <td>
                                                <div class="table-actions justify-content-center">
                                                    <button type="button" class="btn btn--secondary" onclick="database_backup_modification('{{route('admin.business-settings.download-database-backup',[$file_name])}}', '{{translate('Do you really want to download the database locally')}}')">
                                                        <span class="material-icons">download</span> {{translate('Download')}}
                                                    </button>
                                                    <button type="button" class="btn btn--primary" onclick="database_backup_modification('{{route('admin.business-settings.restore-database-backup',[$file_name])}}', '{{translate('Do you really want to restore the database with this file')}}')">
                                                        <span class="material-icons">settings_backup_restore</span> {{translate('Restore')}}
                                                    </button>
                                                    <button type="button" class="btn btn--danger" onclick="database_backup_modification('{{route('admin.business-settings.delete-database-backup',[$file_name])}}', '{{translate('Do you really want to delete this file')}}')">
                                                        <span class="material-icons">delete</span> {{translate('Remove')}}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td class="text-center" colspan="3">{{translate('No backup of the database has been taken yet')}}</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function database_backup_modification(route, message) {
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: message,
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    console.log(route)
                    location.href = route;
                }
            })
        }
    </script>
@endpush
