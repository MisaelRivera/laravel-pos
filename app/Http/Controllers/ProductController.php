<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index (Request $request)
    {
        $categories = Category::all();
        $products = Product::paginate(10);
        return Inertia::render('products/Index', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function store (Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'category_id' => 'required',
            'img_uri' => 'required',
        ], [
            'name.required' => 'Ingrese el nombre del producto',
            'purchase_price.required' => 'Ingrese el precio de compra del producto',
            'sale_price.required' => 'Ingrese el precio de venta del producto',
            'category_id.required' => 'Elija una categoria',
            'img_uri.required' => 'Elija una imagen',
        ]);

        if ($request->file('img_uri')) {
            dd('Hasta aqui');
        }

    }
}
