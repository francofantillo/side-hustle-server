<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function products(Request $request) {
        $query = Product::with('product_images')->where('type', $request->type);
        return customDatatableResponse($query, $request);
    }

    public function productDetail($id) {
        $product = Product::with('product_images', 'product_owner', 'orders')
            ->where('type', 'Product')
            ->find($id);
        if ($product == null) return abort(404);
        return view('admin.products.detail', compact('product'));
    }

    public function productOrders(Request $request) {
        $orders = Order::whereHas('orderDetails', function ($q) use ($request) {
            $q->where('product_id', $request->id)->select('order_id', 'product_id');
        })
        ->with(['orderDetails' => function ($query) use ($request) {
            $query->select('order_id', 'product_qty', 'product_subtotal_price')
                ->where('product_id', $request->id);
        }]);
        return customDatatableResponse($orders, $request);
    }

    public function serviceDetail($id) {
        $product = Product::with('product_images', 'product_owner', 'orders')
            ->where('type', 'Service')
            ->find($id);
        if ($product == null) return abort(404);
        return view('admin.services.detail', compact('product'));
    }
}