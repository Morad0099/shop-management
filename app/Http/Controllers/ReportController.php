<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $dailySales = Sale::whereDate('sale_date', now())->sum('total_price');
        $weeklySales = Sale::whereBetween('sale_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_price');
        $monthlySales = Sale::whereMonth('sale_date', now()->month)->sum('total_price');
        $topProducts = Sale::with('product')
            ->selectRaw('product_id, sum(quantity) as total_quantity')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return view('modules.reports.index', compact('dailySales', 'weeklySales', 'monthlySales', 'topProducts'));
    }
}

