<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $categoryService;
    protected $productService;

    public function __construct(
        CategoryService $categoryService,
        ProductService $productService,
    ) {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    public function index()
    {
        $categories = $this->categoryService->getCategories(4);
        $products = $this->productService->searchProduct();
        // $categories = Category::select('categories.*','products.name as productName')
        //     ->join('products','categories.id', '=','products.category_id')
        //     ->get();
        return view('website.welcome', compact('categories', 'products'));
    }
    public function searchProductByCategory(Request $request){
        $data = $this->productService->searchProduct('', $request->categoryId, $request->paginate, $request->status);
        return view('website.product.listProductsHome', compact('data'));

    }
}
