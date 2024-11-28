<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use PDF; 
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
    public function index(Request $request)
{
    $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
    $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

    $dailySales = Sale::whereDate('sale_date', now())->sum('total_price');
    $weeklySales = Sale::whereBetween('sale_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_price');
    $monthlySales = Sale::whereMonth('sale_date', now()->month)->sum('total_price');
    
    $topProducts = Sale::with('product')
        ->selectRaw('product_id, sum(quantity) as total_quantity')
        ->groupBy('product_id')
        ->orderBy('total_quantity', 'desc')
        ->limit(5)
        ->get();

    $detailedReports = Sale::with('product')
        ->selectRaw('product_id, sum(quantity) as total_quantity, sum(total_price) as total_revenue')
        ->groupBy('product_id')
        ->orderBy('total_quantity', 'desc')
        ->paginate(10);

    $salesChartLabels = Sale::whereBetween('sale_date', [$startDate, $endDate])
        ->groupBy('sale_date')
        ->orderBy('sale_date')
        ->pluck('sale_date');

        $salesChartData = Sale::whereBetween('sale_date', [$startDate, $endDate])
        ->selectRaw('sale_date, sum(total_price) as total_sales')
        ->groupBy('sale_date')
        ->orderBy('sale_date')
        ->pluck('total_sales');
    

    return view('modules.reports.index', compact(
        'dailySales', 
        'weeklySales', 
        'monthlySales', 
        'topProducts', 
        'detailedReports', 
        'salesChartLabels', 
        'salesChartData'
    ));
}

public function export($type)
{
    $sales = Sale::with('product')
        ->select('product_id', 'quantity', 'total_price', 'sale_date')
        ->get();

    if ($type === 'pdf') {
        $pdf = PDF::loadView('modules.reports.pdf', compact('sales'));
        return $pdf->download('sales_report.pdf');
    } elseif ($type === 'csv') {
        $csvData = $sales->map(function ($sale) {
            return [
                'Product' => $sale->product->name ?? 'N/A',
                'Quantity' => $sale->quantity,
                'Total Price' => $sale->total_price,
                'Sale Date' => $sale->sale_date,
            ];
        });

        $csvFile = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sales_report.csv"');

        fputcsv($csvFile, ['Product', 'Quantity', 'Total Price', 'Sale Date']);
        foreach ($csvData as $row) {
            fputcsv($csvFile, $row);
        }
        fclose($csvFile);
        exit;
    }

    return back()->with('error', 'Invalid export type specified.');
}

}

