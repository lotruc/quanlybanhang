<?php

namespace App\Services;

use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;


class ProductService extends BaseService
{
    public function getLimitProducts()
    {
        try {
            $products = Product::where('status', 1)->take(6)->latest()->get();
            return $products;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function searchProduct($searchName = null, $categoryId = null, $paginate = 2, $status = null)
    {
        try {
            $products = Product::select('products.*', 'categories.name as categoryName')
                ->join('categories', 'categories.id', '=', 'products.category_id');
            if ($searchName != null && $searchName != '') {
                $products->where('products.name', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('products.price', 'LIKE', '%' . $searchName . '%');
            }
            if ($categoryId != null && $categoryId != '') {
                $products->where('products.category_id', $categoryId);
            }
            if ($status != null && $status != '') {
                $products->where('products.status', $status);
            }
            $products = $products->latest()->paginate($paginate);
            return $products;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function createProduct($request)
    {
        try {
            $uploadImage = $this->uploadfile($request->file('image'), 'products');

            $product = [
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'quantity' => $request->quantity,
                'image' => $uploadImage,
                'status' => $request->statusProduct,
                'description' => $request->description,
            ];

            Product::create($product);
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function updateProduct($request)
    {
        try {
            $product = Product::findOrFail($request->productId);

            if (!empty($request->file('image'))) {
                $this->deleteFile($product->image);
                $uploadImage = $this->uploadFile($request->file('image'), 'products');
            }
            $productArr = [
                'name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'image' => $uploadImage ?? $product->image,
                'status' => $request->statusProduct
            ];

            $product->update($productArr);

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $data = Product::findOrFail($id);

            $this->deleteFile($data->image);
            $data->delete();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }
}
