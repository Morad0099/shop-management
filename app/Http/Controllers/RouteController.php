<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function dashboard()
    {
        $todaySales = Sale::whereDate('created_at', today())->sum('total_price');
        $totalRevenue = Sale::sum('total_price');
        $totalProductsSold = Sale::sum('quantity');
        $averageSaleValue = Sale::count() > 0 ? Sale::sum('total_price') / Sale::count() : 0;

        // Sales data for chart (last 12 months)
        $salesData = Sale::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[] = $salesData[$i] ?? 0;
        }

        // Revenue by product
        $revenueByProduct = Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(sales.total_price) as revenue')
            ->groupBy('products.name')
            ->orderBy('revenue', 'desc')
            ->get();

        // Recent sales and stock changes
        $recentSales = Sale::with('product')->latest()->limit(5)->get();
        $recentStockChanges = StockHistory::with('product')->latest()->limit(5)->get();
        // $recentStockChanges = StockHistory::with('product')->latest()->take(10)->get();

        return view('modules.dashboard.index', compact(
            'todaySales',
            'totalRevenue',
            'totalProductsSold',
            'averageSaleValue',
            'monthlySales',
            'revenueByProduct',
            'recentSales',
            'recentStockChanges'
        ));
    }

    public function getChartData()
    {
        // Fetch total sales per month
        $monthlySales = Sale::selectRaw('MONTH(sale_date) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Fetch revenue by product
        $productRevenue = Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(sales.total_price) as revenue')
            ->groupBy('products.name')
            ->orderBy('revenue', 'desc')
            ->pluck('revenue', 'name');

        return response()->json([
            'monthlySales' => $monthlySales,
            'productRevenue' => $productRevenue,
        ]);
    }

    public function product()
    {
        return view('modules.products.index');
    }

    public function stockHistory(Product $product)
    {
        $stockHistory = $product->stockHistories()->latest()->paginate(10);
        return view('modules.stock_history.stock-history', compact('product', 'stockHistory'));
    }
}
