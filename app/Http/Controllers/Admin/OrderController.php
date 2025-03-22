<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function allOrders(Request $request) {
        $query = Order::with('orderDetails');
        return customDatatableResponse($query, $request);
    }

    public function orderDetail(Request $request, $id) {
        $order = Order::with('user')->find($id);
        if (request()->ajax()) {
            $order = OrderDetail::where('order_id', $request->id);
            $type = $request->type;
            if ($type != null) {
                $order->where('type', $type);
            }
            return customDatatableResponse($order, $request);
        }
        if ($order == null) return abort(404);
        return view('admin.orders.detail', compact('order'));
    }
}