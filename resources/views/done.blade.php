@extends('layouts.masterUser')

@section('content')
<div class="container" style="margin-top: 100px;">
    <div class="row mb-5">
        <h3 class="font-bold">You Have been match with</h3>
    </div>
    <div class="row justify-content-center d-flex">
        <div class="col-lg-8">
            <div class="card align-items-center">
                <div class="card-body">
                    <img class="img-responsive object-fit-cover rounded mb-3 col-lg-12"
                        src="{{'public/uploads/profile/'.$match->partner->profile_picture}}" alt="Chania" height="345">
                    <h3>{{$match->partner->name}}</h3>
                    <p class="text-sm">
                        {{'Hi, This is my personal info for you, my birthday date is ' . $match->partner->birthday . '
                        and this is my phone number, call me! ' . $match->partner->phone}}
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection