<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService extends BaseService
{
    /**
     * Get order list by status order
     * @param number $statusOrder status order
     * @return Array order list
     */
    public function getOrderByStatus($statusOrder = null)
    {
        try {
            $user = Auth::user();

            $userOrders = Order::select(
                'orders.*',
                'order_items.*',
                'products.name as productName',
                'products.images as productImages'
            )
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('user_id', $user->id);

            if ($statusOrder != null && $statusOrder != '') {
                $userOrders->where('orders.status', '=', $statusOrder);
            }
            $userOrders = $userOrders->orderBy('orders.created_at', 'desc')->paginate(4);

            return $userOrders;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    // hàm đặt hàng
    public function placeOrder($request)
    {
        DB::beginTransaction();

        try {
            $user  = Auth::user();
            $orderCode = $this->generateRandomCode();

            $orderData = [
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'message' => $request->message,
                'user_id' => $user->id,
                'code' => $orderCode,
                'total_order' => $request->total_order
            ];

            // tạo mới đơn hàng
            $order = Order::create($orderData);

            $cart = Cart::where('user_id', Auth::id())->first();

            $cartItems = CartItem::select('cart_items.*', 'products.price as productPrice')
                ->where('cart_id', $cart->id)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->get();
    
            $orderItemData = [];
            foreach ($cartItems as $item) {
                $productQuantity = Product::where('id', $item->product_id)
                    ->first();
                if (!$productQuantity || $productQuantity->quantity < $item->quantity) {
                    DB::rollBack();
                    return response()->json(['error' => 'Không đủ số lượng sản phẩm cho một số sản phẩm. Vui lòng kiểm tra lại']);
                }
    
                $orderItemData[] = [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->productPrice,
                    'quantity' => $item->quantity
                ];
    
                $productQuantity->quantity -= $item->quantity;
                $productQuantity->save();
            }
    
            // thêm thông tin các sản phẩm vào bảng chi tiết đơn hàng
            DB::table('order_items')->insert($orderItemData);

            // xóa tất cả sản phẩm khỏi giỏ hàng
            CartItem::where('cart_id', $cart->id)->delete();

            DB::commit();
            return response()->json(['success' => 'Đặt hàng thành công! Vui lòng kiểm tra đơn mua của bạn']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    
    /**
     * Hàm lấy danh sách đặt hàng
     */
    public function searchOrder($searchName = null, $statusOrder = null)
    {
        try {
            $orders = Order::select('orders.*', 'users.name as username')
                ->join('users', 'users.id', '=', 'orders.user_id');

            if ($searchName != null && $searchName != '') {
                $orders = $orders->where('orders.full_name', 'LIKE', '%' . $searchName . '%')
                    ->orWhere('orders.code', 'LIKE', '%' . $searchName . '%');
            }

            if ($statusOrder != null && $statusOrder != '') {
                $orders->where('orders.status', '=', $statusOrder);
            }

            $orders = $orders->latest()->paginate(6);

            return $orders;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }


    /**
     * Hàm cập nhật trạng thái đơn hàng
     */
    public function updateStatusOrder($request)
    {
        try {
            $newStatus = $request->status;
            $order = Order::findOrFail($request->orderId);
            $currentStatus = $order->status;
            if ($newStatus == 0 ) {
                // chỉ có admin hoặc khi đơn hàng đang ở trạnng thái đang xác nhậnn mới hủy dc
                if (auth()->user()->role === 0 || $order->status == 1) {
                    $order->status = $newStatus;
                    $order->save();
                    return response()->json(['success' => 'Hủy bỏ đơn hàng thành công']);
                } else {
                    return response()->json(['error' => 'Bạn không thể hủy đơn hàng này khi đã đơn hàng đã được xác nhận!']);
                }
            }
            // Cập nhật trạng thái theo thứ tự 
            elseif ($newStatus >= $currentStatus && $newStatus <= ($currentStatus + 1)) {
                $order->status = $newStatus;
                $order->save();
                return response()->json(['success' => 'Cập nhật trạng thái đơn hàng thành công']);
            } else {
                return response()->json(['error' => 'Vui lòng cập nhật theo thứ tự']);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Xóa đơn đặt hàng
     * @return true
     */
    public function deleteOrder($id)
    {
        try {
            $order = Order::findOrFail($id);
            $orderDetail = OrderItem::where('order_id', $order->id);

            $order->delete();
            $orderDetail->delete();

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    /**
     * Hàm lấy thông tin chi tiết đơn hàng
     */
    public function searchDetailsOrder($orderId, $searchName = null, $paginate = 3)
    {
        try {
            $order = Order::findOrFail($orderId);

            $orderDetail = OrderItem::select(
                'order_items.order_id',
                'order_items.quantity',
                'order_items.price',
                'products.name as productName',
                'products.image as productImage'
            )
                ->selectRaw('SUM(order_items.quantity * order_items.price) as total')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->where('order_id', $order->id)
                ->groupBy(
                    'order_items.order_id',
                    'order_items.quantity',
                    'order_items.price',
                    'products.name',
                    'products.image'
                );

            if ($searchName != null && $searchName != '') {
                $orderDetail = $orderDetail->where('products.name', 'LIKE', '%' . $searchName . '%');
            }

            if ($paginate != null && $paginate != '') {
                $orderDetail = $orderDetail->paginate($paginate);
            } else {
                $orderDetail = $orderDetail->get();
            }
            return $orderDetail;
        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e, 500);
        }
    }

    
}