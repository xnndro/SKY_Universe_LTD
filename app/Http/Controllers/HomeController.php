<?php

namespace App\Http\Controllers;

use App\Models\MatchPartner;
use App\Models\Orders;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->role_id == 1){
            $total_users = User::where('role_id', 2)->count();
            $total_sales = Orders::where('payment_status', 'success')->sum('total_price');
            return view('pages.dashboard',compact('total_users','total_sales'));
        }else{
            $match = MatchPartner::where('user_id', Auth::user()->id)->orWhere('partner_id', Auth::user()->id)->first();
            if($match){
                $orders = Orders::where('match_id', $match->id)->first();
                if($orders){
                    if($orders->payment_status == 'success'){

                        return view('done',compact('match')); 
                    }else
                    {
                        return view('checkout', compact('orders'));
                    }
                }else
                {
                    return redirect()->route('places');
                }
            }else
            {
                return view('coba');
            }

        }
    }

    public function sales()
    {
        $orders = Orders::where('payment_status', 'success')->get();
        return view('pages.sales', compact('orders'));
    }

    public function userList()
    {
        $users = User::where('role_id', 2)->get();
        return view('pages.userList', compact('users'));
    }

    public function banned($id)
    {
        $user = User::find($id);
        $user->banned = 'banned';
        $user->save();

        return redirect()->route('userList');
    }
    public function activate($id)
    {
        $user = User::find($id);
        $user->banned = 'no';
        $user->save();

        return redirect()->route('userList');
    }
}
