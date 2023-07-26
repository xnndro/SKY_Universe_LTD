@extends('layouts.masterUser')

@section('content')
    <div class="container" style="margin-top: 100px;">
        <div class="row mb-5">
            <h3 class="font-bold">The perfect choice, lets checkout this</h3>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <h4 class="mb-3">Your partner</h4>
                <img class="img-responsive object-fit-cover rounded mb-3 col-lg-12" src="{{'public/uploads/profile/'.$orders->match->partner->profile_picture}}" alt="Chania" height="345"> 
                <h3>{{$orders->match->partner->name}}</h3>
                <p class="text-sm">
                    {{'Hi, This is my personal info for you, my birthday date is ' . $orders->match->partner->birthday . ' and this is my phone number, call me! ' . $orders->match->partner->phone}}
                </p>
{{--                 
                <div class="card">
                    <div class="card-header">Detail Checkout</div>
                    <div class="card-body">
                        <h5>User Name: {{ $orders->match->user->name }}</h5>
                        <h5>Partner Name: {{ $orders->match->partner->name }}</h5>
                        <h5>Place Name: {{ $orders->place->name }}</h5>
                        <h5>Total Price: {{ $orders->total_price }}</h5>
                    </div>
                </div> --}}
            </div>
            <div class="col-lg-2">
                
            </div>
            <div class="col-lg-5">
                <h4 class="mb-3">Your place choice</h4>
                <img class="img-responsive object-fit-cover rounded mb-3 col-lg-12" src="{{'storage/uploads/places/'.$orders->place->picture}}" alt="Chania" height="345"> 
                
                <h5 class="card-title">{{ $orders->place->name }}</h5>
                        <p class="card-text">{{ $orders->place->address }}</p>
                        <h3 class="font-bold mt-n2 mb-n4">{{
                            'Rp '.number_format($orders->place->price, 0, ',', '.')
                            }}</h3>
            </div>

            
        </div>
        
        <div class="col-lg-12 d-flex justify-content-end">
         <form action="{{'session'}}" method="POST">
             @csrf   
             <input type="hidden" name="_token" value="{{ csrf_token() }}">
             <input type="hidden" name="orderID" value="{{$orders->order_transaction_id}}">
             <input type="hidden" name="total_price" value="{{$orders->total_price}}">
             <button type="submit" class="btn btn-primary">Checkout</button>
         </form>
        </div>
        
    </div>


    
@endsection
