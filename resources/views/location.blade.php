@extends('layouts.masterUser')
@section('content')

    <div class="container" style="margin-top:100px;">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="btn-group" role="group" aria-label="Filter by location">
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="all">All</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="Singapore">Singapore</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="Jakarta">Jakarta</button>
                    <button type="button" class="btn btn-outline-primary filter-btn" data-filter="Tangerang">Tangerang</button>
                </div>
            </div>
        </div>

        <div class="row" id="card-container">
            @foreach($places as $place)
            
            <div class="col-md-4 mb-4 card-item {{ strtolower($place->location) }}">
                <div class="card">
                    <img src="{{ 
                        asset('/storage/uploads/places/'. $place->picture)
                        }}" class="card-img-top" alt="{{ $place->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $place->name }}</h5>
                        <p class="card-text">{{ $place->address }}</p>
                        <h3 class="font-bold mt-n2 mb-n4">{{
                            'Rp '.number_format($place->price, 0, ',', '.')
                            }}</h3>
                    </div>
                    {{-- btn checkout --}}
                    <div class="card-footer">
                        <a href="{{ route('checkout', $place->id) }}" class="btn btn-primary">Book This Place</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function filterBy(filterValue) {
            // Show/hide items based on the filter value
            if (filterValue === 'all') {
                $('.card-item').show();
            } else {
                $('.card-item').hide();
                $('.' + filterValue.toLowerCase()).show();
            }

            // Toggle active class for the clicked button
            $('.filter-btn').removeClass('btn-primary');
            $('.filter-btn[data-filter="' + filterValue + '"]').toggleClass('btn-primary');
        }
    
        $(document).ready(function() {
            // Filter button click event
            $('.filter-btn').on('click', function() {
                var filterValue = $(this).data('filter');
                filterBy(filterValue);
            });
        });
    </script>
    <style>
        /* Custom styles to set text color to white for btn-primary */
        .btn-primary {
            color: #ffffff;
        }
    </style>
@endsection
