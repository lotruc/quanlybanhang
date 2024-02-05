<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * hàm hiển thị giao diện trang giỏ hàng
     * @return view cart page
     */
    public function index()
    {
        return view('website.cart.index');
    }

    /**
     * hàm hiển thị bảng giỏ hàng
     */
    public function search()
    {
        $data = $this->cartService->showCart();
        return view('website.cart.tableCart', [
            'cartItems' => $data['cartItems'],
            'totalCarts' => $data['totalCarts'],
        ]);
    }
     /**
     * Hàm thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(CartRequest $request)
    {
        $data = $this->cartService->addToCart($request);
        return response()->json(['data' => $data]);
    }

     /**
     * Hàm cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateCart(CartRequest $request)
    {
        $data = $this->cartService->updateCart($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Remove product from cart
     * @param Request $request
     * @return response data message status
     */
    public function removeProductFromCart(Request $request)
    {
        $data = $this->cartService->removeProductFromCart($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Hàm lấy tổng sản phẩm trong giỏ hàng
     */
    public function getTotalProductInCart()
    {
        $data = $this->cartService->getTotalProductInCart();
        return response()->json(['data' => $data]);
    }
}
