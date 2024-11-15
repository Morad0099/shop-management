<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function dashboard()
    {
        $recentStockChanges = StockHistory::with('product')->latest()->take(10)->get();

        return view('modules.dashboard.index', compact('recentStockChanges'));
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
