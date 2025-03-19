@extends('layouts.admin')

@section('title', 'System Information')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">System Information</h1>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group">
            <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-cog me-2"></i> General Settings
            </a>
            <a href="{{ route('admin.settings.system-info') }}" class="list-group-item list-group-item-action active">
                <i class="fas fa-info-circle me-2"></i> System Information
            </a>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">System Information</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width: 30%">PHP Version</th>
                                <td>{{ $phpVersion }}</td>
                            </tr>
                            <tr>
                                <th>Laravel Version</th>
                                <td>{{ $laravelVersion }}</td>
                            </tr>
                            <tr>
                                <th>Server Software</th>
                                <td>{{ $serverSoftware }}</td>
                            </tr>
                            <tr>
                                <th>Database Type</th>
                                <td>{{ $databaseType }}</td>
                            </tr>
                            <tr>
                                <th>Database Version</th>
                                <td>{{ $databaseVersion }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

