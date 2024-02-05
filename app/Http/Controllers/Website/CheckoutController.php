<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        $data = $this->cartService->showCartCheckout();
        return view('website.checkout.index', [
            'cartItemsInCheckout' => $data['cartItemsInCheckout'],
            'totalCarts' => $data['totalCarts'],
        ]);
    }

    /**
     * Hàm lưu đơn hàng
     */

    public function placeOrder(OrderRequest $request)
    {
        // dd($request->all());
        $data = $this->orderService->placeOrder($request);
        return response()->json(['data' => $data]);
    }
}
