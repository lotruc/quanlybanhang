<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Hiển thị trang quản lý đơn hàng
     */
    public function index()
    {
        return view('admin.order.index');
    }

    /**
     * Hiển thị bảng đơn hàng
     */
    public function search(Request $request)
    {
        $data = $this->orderService->searchOrder($request->searchName);
        return view('admin.order.table', ['data' => $data]);
    }

    /**
     * Cập nhật trạng thái cho đơn hàng
     */
    public function updateStatus(Request $request)
    {
        $data = $this->orderService->updateStatusOrder($request);
        return response()->json(['data' => $data]);
    }

    /**
     * Xóa đơn hàng
     */
    public function delete($id)
    {
        $this->orderService->deleteOrder($id);
        return response()->json('ok');
    }

    /**
     * Hiển thị trang chi tiết đơn hàng
     */
    public function details($id)
    {
        return view('admin.orderDetails.details', compact('id'));
    }

    /**
     * hiển thị bảng chi tiết đơn hàng
     */
    public function searchDetails(Request $request)
    {
        $data = $this->orderService->searchDetailsOrder($request->orderId, $request->searchName);
        return view('admin.orderDetails.table', ['data' => $data]);
    }
}
