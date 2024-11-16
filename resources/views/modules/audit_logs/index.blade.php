@extends('layout.app')

@section('title', 'Audit Logs')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-4">
        <h6>Audit Logs</h6>

        <!-- Filter Form -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="user" class="form-select">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="module" class="form-select">
                        <option value="">All Modules</option>
                        <option value="Products">Products</option>
                        <option value="Users">Users</option>
                        <option value="Sales">Sales</option>
                        <option value="Low Stock">Low Stock</option>
                        <option value="Dashboard">Dashboard</option>
                        <option value="Reports">Reports</option>
                        <option value="User Management">User Management</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('audit-logs.export', ['type' => 'pdf']) }}" class="btn btn-sm btn-primary me-2">Export PDF</a>
            <a href="{{ route('audit-logs.export', ['type' => 'csv']) }}" class="btn btn-sm btn-success">Export CSV</a>
        </div>

        <!-- Audit Logs Table -->
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Details</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->user->name ?? 'System' }}</td>
                            <td><span class="badge bg-primary">{{ $log->action }}</span></td>
                            <td>{{ $log->module }}</td>
                            <td>
                                @php
                                    $details = json_decode($log->details, true);
                                @endphp
                                @if (is_array($details))
                                    <ul class="mb-0">
                                        @foreach ($details as $key => $value)
                                            @if (is_array($value))
                                                <strong>{{ ucfirst($key) }}:</strong>
                                                <ul>
                                                    @foreach ($value as $subKey => $subValue)
                                                        @if (is_array($subValue))
                                                            <li>
                                                                @foreach ($subValue as $innerKey => $innerValue)
                                                                    {{ ucfirst($innerKey) }}: {{ $innerValue }}{{ !$loop->last ? ',' : '' }}
                                                                @endforeach
                                                            </li>
                                                        @else
                                                            <li>{{ ucfirst($subKey) }}: {{ $subValue }}</li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @else
                                                <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    {{ $log->details }}
                                @endif
                            </td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $logs->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
