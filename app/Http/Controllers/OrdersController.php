<?php

namespace App\Http\Controllers;

use App\Models\MatchPartner;
use App\Models\Orders;
use App\Models\Places;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function checkout($id)
    {
        // get match id of user_id
        $match_id = MatchPartner::where('user_id',Auth::user()->id)->orWhere('partner_id',Auth::user()->id)->first();
        // got match id and save to variable
        $match_id = $match_id->id;

        // insert into orders table
        $order = new Orders();
        $order->match_id = $match_id;
        $order->place_id = $id;
        $order->order_transaction_id = rand(100000,999999);
        $order->payment_status = 'pending';
        $order->total_price = Places::where('id',$id)->first()->price;
        $order->save();

        return redirect()->route('index.orders');
    }
    
    public function index(){

        // get order by check the match is user_id or partner_id is same with auth user id
        $orders = Orders::whereHas('match',function($q){
            $q->where('user_id',Auth::user()->id)->orWhere('partner_id',Auth::user()->id);
        })->first();

        return view('checkout',compact('orders'));
    }

    public function session(Request $request)
    {
     
        \Stripe\Stripe::setApiKey('sk_test_51NXT5YFSLbwC00tLfIG0WS4FTAYSuTYS1KxJdIOENVjkQaBgzyYR4BQc6eCpDSampJCqH7PuYn1bwzCfOppnSTJS00uT4dw4pa');
        $order_id = $request->orderID;
        $total_price = $request->total_price;

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'idr',
                    'unit_amount' => $total_price,
                    'product_data' => [
                        'name' => 'Order ID: '.$order_id,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('success', ['id' => $order_id]),
            'cancel_url' => route('index.orders'),
        ]);

        return redirect()->away($checkout_session->url);
    }

    public function success($id)
    {
        $order = Orders::where('order_transaction_id',$id)->first();
        $order->payment_status = 'success';
        $order->save();
        return view('success');
    }

    // public function delete(Request $request)
    // {
    //     $order = Orders::where('order_transaction_id',$request->orderID)->first();
    //     $order->delete();
    //     return redirect()->back();
    // }
}
