<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LowStockController extends Controller
{
    public function index()
    {
        // Fetch products with low stock, e.g., stock <= 10
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->paginate(10);

        return view('modules.low_stock.index', compact('lowStockProducts'));
    }

    public function export($type)
    {
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        if ($type === 'csv') {
            return $this->exportCsv($lowStockProducts);
        } elseif ($type === 'pdf') {
            return $this->exportPdf($lowStockProducts);
        }

        return redirect()->back()->with('error', 'Invalid export type selected.');
    }

    private function exportCsv($products)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="low_stock_products.csv"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Add CSV header
            fputcsv($file, ['Product Name', 'Current Stock', 'Category', 'Last Updated']);

            // Add product data
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->stock,
                    $product->category,
                    $product->updated_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    private function exportPdf($products)
    {
        $pdf = Pdf::loadView('modules.low_stock.low_stock_pdf', compact('products'));
        return $pdf->download('low_stock_products.pdf');
    }
}

