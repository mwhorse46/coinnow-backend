<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductSellerRelation;
use App\Models\Seller;
use App\Models\Special;

class HistoryController extends Controller
{
    public function index()
    {
        $records = Notification::select('id', 'quantity', 'price', 'type', 'seen', 'created_at', 'product_id', 'seller_id')
            ->with(array('product' => function ($query) {
                $query->select('id', 'image')->with('productDescription:id,name,product_id');
            }))->with(['seller' => function ($query) {
            $query->select('id', 'email');
        }])->whereIn('type', ['item_sell_auto', 'special_item_sell_auto'])
            ->orderBy('notification.created_at', 'DESC')->paginate($this->defaultPaginate);
        return view('admin.history.index', ['records' => $records]);
    }

    public function transaction($id)
    {

        $total_received = 0;
        $total_sent = 0;
        $current_balance = 0;
        $inventory_worth = 0;
        if ($id) {
            $seller = Seller::where('id', $id)->first();
            $products = $seller->products;
            $current_balance = $seller->balance;
            foreach ($products as $key => $inventory) {
                if ($inventory->pivot->quantity > 0) {
                    $inventory_worth += $inventory->pivot->quantity * $inventory->price;
                }
            }
            $records = Notification::select('id', 'quantity', 'amount', 'type', 'seen', 'created_at', 'product_id', 'seller_id', 'receiver_id', 'sender_id')
                ->where('seller_id', $id)
                ->with(array('product' => function ($query) {
                    $query->select('id', 'image')->with('productDescription:id,name,product_id');
                }))->with(['seller' => function ($query) {
                $query->select('id', 'email');
            }])->with(['receiver' => function ($query) {
                $query->select('id', 'email');
            }])->whereIn('type', ['send_coin', 'receive_coin'])->get();
            for ($i = 0; $i < count($records); $i++) {
                if ($records[$i]->type == 'send_coin') {
                    $total_sent += $records[$i]->amount;
                }
                if ($records[$i]->type == 'receive_coin') {
                    $total_received += $records[$i]->amount;
                }
            }
            $records = Notification::select('id', 'quantity', 'amount', 'type', 'seen', 'created_at', 'product_id', 'seller_id', 'receiver_id', 'sender_id')
                ->where('seller_id', $id)
                ->with(array('product' => function ($query) {
                    $query->select('id', 'image')->with('productDescription:id,name,product_id');
                }))->with(['seller' => function ($query) {
                $query->select('id', 'email');
            }])->with(['receiver' => function ($query) {
                $query->select('id', 'email');
            }])->whereIn('type', ['send_coin', 'receive_coin'])
                ->orderBy('notification.created_at', 'DESC')->paginate($this->defaultPaginate);
        } else {
            $inventory_worth = 0;
            $records = Product::select('id', 'image', 'category_id', 'model', 'price', 'min_price', 'max_price', 'location', 'quantity', 'sort_order', 'status', 'points');
            // $records = $user->hasRole('Admin') || empty($seller) ? $records->where('seller_id', 0)->orWhereNull('seller_id') : $records->where('seller_id', 1);
            $records = $records->get();
            for ($i = 0; $i < count($records); $i++) {
                if ($records[$i]['points'] > 0) {
                    $sum = Special::where('product_id', $records[$i]->id)->sum('quantity');
                } else {
                    $sum = ProductSellerRelation::where([['product_id', $records[$i]->id]])->sum('quantity');
                }
                $inventory_worth += $sum * $records[$i]['price'];
            }
            $current_balance = Seller::whereNotNull('balance')->sum('balance');
            $records = Notification::select('id', 'quantity', 'amount', 'type', 'seen', 'created_at', 'product_id', 'seller_id', 'receiver_id', 'sender_id')
                ->with(array('product' => function ($query) {
                    $query->select('id', 'image')->with('productDescription:id,name,product_id');
                }))->with(['seller' => function ($query) {
                $query->select('id', 'email');
            }])->with(['receiver' => function ($query) {
                $query->select('id', 'email');
            }])->whereIn('type', ['send_coin', 'receive_coin'])->get();
            for ($i = 0; $i < count($records); $i++) {
                if ($records[$i]->type == 'send_coin') {
                    $total_sent += $records[$i]->amount;
                }
                if ($records[$i]->type == 'receive_coin') {
                    $total_received += $records[$i]->amount;
                }
            }
            $records = Notification::select('id', 'quantity', 'amount', 'type', 'seen', 'created_at', 'product_id', 'seller_id', 'receiver_id', 'sender_id')
                ->with(array('product' => function ($query) {
                    $query->select('id', 'image')->with('productDescription:id,name,product_id');
                }))->with(['seller' => function ($query) {
                $query->select('id', 'email');
            }])->with(['receiver' => function ($query) {
                $query->select('id', 'email');
            }])->whereIn('type', ['send_coin', 'receive_coin'])
                ->orderBy('notification.created_at', 'DESC')->paginate($this->defaultPaginate);
        }
        return view('admin.history.transaction', ['records' => $records, 'total_received' => $total_received, 'total_sent' => $total_sent, 'total' => true, 'inventory_worth' => $inventory_worth, 'current_balance' => $current_balance]);
    }
}
