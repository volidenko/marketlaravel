<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $roots = Category::where('parent_id', 0)->get();  // корневые категории
        $brands = Brand::popular(); // популярные бренды
        return view('catalog.index', compact('roots', 'brands'));
    }

    public function category(Category $category)
    {
        $descendants = $category->getAllChildren($category->id); // получаем всех потомков категории
        $descendants[] = $category->id;
        $products = Product::whereIn('category_id', $descendants)->paginate(10); // товары категории и всех потомков
        return view('catalog.category', compact('category', 'products'));
    }

    public function brand(Brand $brand)
    {
        $products = $brand->products()->paginate(10);
        return view('catalog.brand', compact('brand', 'products'));
    }

    public function product(Product $product)
    {
        //$product = Product::where('slug', $slug)->firstOrFail();
        // $category = $product->getCategory();
        // $brand = $product->getBrand();
        return view('catalog.product', compact('product'));
    }
}
