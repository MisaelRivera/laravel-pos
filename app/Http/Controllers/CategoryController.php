<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index ()
    {
        $categoriesProp = Category::orderBy('name')->get();
        return Inertia::render('categories/Index', [
            'categoriesProp' => $categoriesProp
        ]);
    }

    public function store (Request $request)
    {
        $category = $request->validate([
            'name' => 'required|unique:categories,name',
        ]);
        Category::create($category);
        $request->session()->flash('message', 'Se ha creado una categoria correctamente');
        return redirect()->route('categories.index');
    }
}
