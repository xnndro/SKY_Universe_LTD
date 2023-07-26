@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header pb-0 px-3">
          <h6 class="mb-0">Billing Information</h6>
        </div>
        <div class="card-body pt-4 p-3">
          <ul class="list-group">
            @if($orders->count() == 0)
            <div class="d-flex justify-content-center align-items-center flex-wrap mt-5">
                <div class="text-center">
                    <h5 class="mt-n5">Belum ada transaksi</h5>
                </div>
            </div>
            @else
            @foreach($orders as $order)
            <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                <div class="d-flex flex-column">
                  <h6 class="mb-3 text-sm">{{'OrderID: '. $order->order_transaction_id}}</h6>
                  <span class="mb-2 text-xs">User Name: <span class="text-dark font-weight-bold ms-sm-2">{{$order->match->user->name}}</span></span>
                  <span class="mb-2 text-xs">Partner Name: <span class="text-dark ms-sm-2 font-weight-bold">{{$order->match->partner->name}}</span></span>
                  <span class="text-xs">Places: <span class="text-dark ms-sm-2 font-weight-bold">{{$order->place->name}}</span></span>
                </div>
                <div class="ms-auto text-end">
                  <div class="d-flex align-items-center text-success text-gradient text-xl font-weight-bold">
                      {{ 'Rp ' . number_format($order->total_price, 0, ',', '.') }}
                    </div>
                </div>
              </li>
            @endforeach
           @endif
          </ul>
        </div>
      </div>
    </div>

  </div>
@endsection