<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index ()
    {
        $categories = Category::orderBy('name')->paginate(5);
        return Inertia::render('categories/Index', [
            'categories' => $categories
        ]);
    }

    public function store (Request $request)
    {
        $category = $request->validate([
            'name' => 'required|unique:categories,name',
        ], [
            'name.required' => 'Ingrese la categoria'
        ]);
        Category::create($category);
        $request->session()->flash('message', 'Se ha creado una categoria correctamente');
        return redirect()->route('categories.index');
    }

    public function update (Request $request, Category $category)
    {
        $validatedCategory = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Ingrese la categoria'
        ]);
        $category->name = $validatedCategory['name'];
        $category->update();
        $request->session()->flash('message', "Se ha editado la categoria $category->name correctamente");
        return redirect()->route('categories.index');
    }

    public function destroy (Category $category)
    {
        $categoryName = $category->name;
        $category->delete();
        return redirect()
            ->route('categories.index')
            ->with('message', "Se ha eliminado la categoria $categoryName correctamente!");
    }
}
