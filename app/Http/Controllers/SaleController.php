<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')
            ->whereDate('sale_date', now()->toDateString()) // Filter sales for the current day
            ->orderBy('sale_date', 'desc')
            ->paginate(10);

        return view('modules.sales.index', compact('sales'));
    }

    public function show(Request $request)
    {
        $query = Sale::with('product');

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('sale_date', $request->date);
        }

        $sales = $query->orderBy('sale_date', 'desc')->paginate(10);

        return view('modules.sales.history', compact('sales'));
    }



    public function create()
    {
        $products = Product::all();
        return view('modules.sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);

        $errorMessages = [];

        foreach ($validated['products'] as $index => $productData) {
            $product = Product::findOrFail($productData['product_id']);

            // Check stock availability
            if ($productData['quantity'] > $product->stock) {
                $errorMessages[] = "The quantity for '{$product->name}' exceeds available stock ({$product->stock}).";
                continue;
            }
        }

        // If there are errors, return back with the error messages
        if (!empty($errorMessages)) {
            return redirect()->back()->withErrors(['products' => $errorMessages])->withInput();
        }

        // Proceed with the sale creation
        foreach ($validated['products'] as $productData) {
            $product = Product::findOrFail($productData['product_id']);

            // Update stock
            $newStock = $product->stock - $productData['quantity'];
            $product->update(['stock' => $newStock]);

            // Log stock history
            StockHistory::create([
                'product_id' => $product->id,
                'quantity_changed' => -$productData['quantity'],
                'new_stock' => $newStock,
                'reason' => 'Sale',
            ]);

            // Record the sale
            Sale::create([
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'total_price' => $product->price * $productData['quantity'],
                'attendant' => Auth::user()->email,
                'sale_date' => $validated['sale_date'],
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Sale(s) recorded and stock updated successfully.');
    }




    public function destroy(Sale $sale)
    {
        try {
            $sale->delete();

            return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sales.index')->with('error', 'An error occurred while deleting the sale.');
        }
    }
}
