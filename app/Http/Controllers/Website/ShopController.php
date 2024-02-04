<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $productService;
    protected $categoryService;
    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $categories = $this->categoryService->getCategories();
        return view('website.product.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $data = $this->productService->searchProduct('', $request->categoryId, $request->paginate, $request->status);
        return view('website.product.listProducts', compact('data'));
    }

    public function details($id)
    {
        $product = Product::select('products.*', 'categories.name as categoryName')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('products.status', '1')->where('products.id', $id)->first();
        return view('website.product.details', compact('product'));
    }
}
