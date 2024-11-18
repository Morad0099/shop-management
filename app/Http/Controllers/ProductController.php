<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply name filter
        if ($request->has('name') && $request->name) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        // Apply category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Paginate results
        $products = $query->paginate(10);

        // Get unique categories for the filter dropdown
        $categories = Product::pluck('category')->unique();

        return view('modules.products.index', compact('products', 'categories'));
    }


    public function create()
    {
        return view('modules.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->storeAs(
                'products',
                time() . '_' . $request->file('image')->getClientOriginalName(),
                'public_uploads' // A custom disk pointing to 'public/uploads' in filesystem.php
            );
            $validated['image'] = config('app.url') . '/uploads/' . $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }


    public function edit(Product $product)
    {
        return view('modules.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,WEBP|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                $relativePath = str_replace(config('app.url') . '/uploads/', '', $product->image); // Remove full URL part
                Storage::disk('public_uploads')->delete($relativePath);
            }
    
            // Store new image and concatenate app URL
            $path = $request->file('image')->storeAs(
                'products',
                time() . '_' . $request->file('image')->getClientOriginalName(),
                'public_uploads' // Custom disk
            );
            $validated['image'] = config('app.url') . '/uploads/' . $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }


    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
