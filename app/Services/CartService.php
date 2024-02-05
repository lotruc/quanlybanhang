<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartService extends BaseService
{
   
    /**
     * hàm xử lý thêm sản phẩm vào giỏ hàng
     */
    public function addToCart($request)
    {
        DB::beginTransaction();

        try {
            $product = Product::find($request->productId);

            if (!$product) {
                return response()->json(['error' => 'Sản phẩm không có']);
            }
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            // kiểm tra xem đã tồn tại giỏ hàng hay chưa, nếu chưa thì tạo mới
            if (!$cart) {
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->save();
            }

            // kiểm tra xem sản phẩm có tồn tại và có số lượng > 0 hay không
            $productAvailable = Product::where('id', $product->id)
                ->where('quantity', '>', 0)
                ->first();

            if (!$productAvailable) {
                return response()->json(['error' => 'Sản phẩm này đã hết hàng']);
            }


            // kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng hay chưa
            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            $newQuantity = intval($request->quantity);

            // nếu sp tồn tại thì chỉ tăng sô lượng lên
            if ($existingCartItem) {
                // kiểm tra số lượng sp
                if ($existingCartItem->quantity + $newQuantity > $productAvailable->quantity) {
                    return response()->json(['error' => 'Số lượng sản phẩm trong giỏ hàng của bạn vượt quá số lượng trong kho. Xin lỗi vì sự bất tiện này']);
                }
                $existingCartItem->quantity += $newQuantity;
                $existingCartItem->save();
            } else {
                // nếu chưa thì tạo mới chi tiết gior hàng
                // kiểm tra số lượng sp
                if ($newQuantity > $productAvailable->quantity) {
                    return response()->json(['error' => 'Số lượng sản phẩm trong giỏ hàng của bạn vượt quá số lượng trong kho. Xin lỗi vì sự bất tiện này']);
                }

                $cartItem = new CartItem();
                $cartItem->cart_id = $cart->id;
                $cartItem->product_id = $product->id;
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            }

            DB::commit();

            return response()->json(['success' => 'Đã thêm sản phẩm thành công']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Hàm lấy danh sách sản phẩm trong giỏ hàng
     */
    public function showCart()
    {
        try {
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->save();
            }
            //lấy danh sách sản phẩm trong giỏ hàng
            $cartItems = CartItem::where('cart_id', $cart->id)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->select(
                    'cart_items.cart_id',
                    'cart_items.quantity as cartQuantity',
                    'products.id as productId',
                    'products.name as productName',
                    'products.price as productPrice',
                    'products.image as productImage',
                    'products.quantity as productQuantity',
                )
                ->selectRaw('SUM(cart_items.quantity * products.price) as total')
                ->groupBy('cart_items.cart_id', 'cart_items.quantity', 'products.quantity','products.id', 'products.name', 'products.price', 'products.image')
                ->orderBy('cart_items.created_at', 'desc')
                ->get();

            // khai báo giá trị tổng tiền dơn hàng
            $totalCarts = 0;

            // lặp qua danh sách sp trong giỏ hàng để tính tổng tiền đơn hàng
            foreach ($cartItems as $item) {
                if ($item->quantity <= $item->productQuantity) {
                    $totalCarts += $item->total;
                }
            }

            return [
                'cartItems' => $cartItems,
                'totalCarts' => $totalCarts
            ];
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }



    /**
     * Hàm lấy danh sách giỏ hàng cho trang thanh toán
     */
    public function showCartCheckout()
    {
        try {
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->save();
            }
            // lấy danh sách giỏ hàng cho trang thanh toán
            $cartItemsInCheckout = CartItem::where('cart_id', $cart->id)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->select(
                    'cart_items.cart_id',
                    'cart_items.quantity',
                    'products.id as productId',
                    'products.name as productName',
                    'products.price as productPrice',
                    'products.image as productImage',
                    'products.quantity as productQuantity',
                )
                ->selectRaw('SUM(cart_items.quantity * products.price) as total')
                ->groupBy('cart_items.cart_id', 'cart_items.quantity', 'products.quantity','products.id', 'products.name', 'products.price', 'products.image')
                ->orderBy('cart_items.created_at', 'desc')
                ->get();


            // lặp qua ds sp trong giỏ hàng và kiểm tra sl sp
            // nếu sl sp trong giỏ hàng > sl sp trong khi thì sẽ xóa sản phẩm đó khỏi giỏ hàng
            foreach ($cartItemsInCheckout as $key => $item) {
                if ($item->quantity > $item->productQuantity) {
                    CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $item->productId)
                        ->delete();

                    unset($cartItemsInCheckout[$key]);
                }
            }

            $totalCarts = 0;
            foreach ($cartItemsInCheckout as $item) {
                $totalCarts += $item->total;
            }
            
            return [
                'cartItemsInCheckout' => $cartItemsInCheckout,
                'totalCarts' => $totalCarts
            ];
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }


    /**
     * Hàm lấy số lượng sản phẩm trong giỏ hàng
     */
    public function getTotalProductInCart()
    {
        try {
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->save();
            }

            $totalProductInCart = CartItem::where('cart_id', $cart->id)->count();
            return $totalProductInCart;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Hàm xóa sản phẩm ra khỏi giỏ hàng
     */
    public function removeProductFromCart($request)
    {
        try {
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            // lấy sản phẩm trong giỏ hàng cần xóa
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->productId)
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ hàng']);
            }

            // xóa sản phẩm khỏi giỏ hàng
            $cartItem->delete();

            return response()->json(['success' => 'Đã xóa thành công sản phẩm khỏi giỏ hàng']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Cập nhật số lượng sp trong giỏ hàng
     */
    public function updateCart($request)
    {
        DB::beginTransaction();
        try {
            $newQuantity = $request->quantity;
            $user  = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json(['error' => 'Không tìm thấy giỏ hàng']);
            }
            // Sản phẩm trong giỏ hàng cần cập nhật
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->productId)
                ->first();

            if ($cartItem) {
                $product = Product::where('id', $request->productId)
                    ->first();
                // nếu số lượng cập nhật <= số lượng sp trong kho thì tiến hành cập nhật
                if ($newQuantity <= $product->quantity) {
                    $cartItem->quantity = $newQuantity;
                    $cartItem->save();
                    DB::commit();
                    return response()->json(['success' => 'Giỏ hàng được cập nhật thành công']);
                } else {
                    return response()->json(['error' => 'Số lượng mới vượt quá số lượng sản phẩm trong kho. Vui lòng chọn lại']);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }

}
