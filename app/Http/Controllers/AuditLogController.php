<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $logs = AuditLog::with('user')
            ->when($request->user, fn($query) => $query->where('user_id', $request->user))
            ->when($request->module, fn($query) => $query->where('module', $request->module))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('modules.audit_logs.index', compact('logs','users'));
    }

    public function export(Request $request, $type)
    {
        $logs = AuditLog::with('user')
            ->when($request->user, fn($query) => $query->where('user_id', $request->user))
            ->when($request->module, fn($query) => $query->where('module', $request->module))
            ->orderBy('created_at', 'desc')
            ->get();

        if ($type === 'csv') {
            $csvData = $logs->map(function ($log) {
                return [
                    'User' => $log->user->name ?? 'System',
                    'Action' => $log->action,
                    'Module' => $log->module,
                    'Details' => $log->details,
                    'IP Address' => $log->ip_address,
                    'Date' => $log->created_at->format('Y-m-d H:i'),
                ];
            });

            $filename = 'audit_logs.csv';
            return Excel::download(new class($csvData) implements \Maatwebsite\Excel\Concerns\FromCollection {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function collection() { return $this->data; }
            }, $filename);
        }

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('modules.audit_logs.pdf', compact('logs'));
            return $pdf->download('audit_logs.pdf');
        }

        return redirect()->route('audit-logs.index')->with('error', 'Invalid export type.');
    }
}
