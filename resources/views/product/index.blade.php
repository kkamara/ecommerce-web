@extends('layouts.master')

@section('content')


<div class="card">
    <div class="card-body">

        <div class="card-header">
            <form class='form-inline' action="" method='GET'>

                <div class="form-group">
                    <label>£
                        <input type="number" min="0.01" step="0.01" max="2500" name="min_price" class='form-control' placeholder='Min' value='{{ $input['min_price'] or '' }}'>
                    </label>
                </div>

                <div class="form-group">
                    <label>£
                        <input type="number" min="0.01" step="0.01" max="2500" name="max_price" class='form-control' placeholder='Max' value='{{ $input['max_price'] or '' }}'>
                    </label>
                </div>

                {{-- add input for average review --}}

                <div class="form-group">
                    <label>
                        <input name='query' type="text" class='form-control' placeholder="Search..." value='{{ $input['query'] or '' }}'>
                    </label>
                </div>

                <div class="form-group">
                    <input class='form-control btn btn-success' type="submit">
                </div>
            </form>
        </div>

        <div class="card-text">

            <div class="list-group">

                @forelse($products as $product)

                <a href="{{ $product->path }}" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                    <img style='max-height:100px' src="{{ $product->image_path }}" class='img-responsive'>
                    <h5 class="mb-1">{{ $product->name }}</h5>
                    <h3>
                        <strong>
                            {{ $product->formatted_cost }}
                        </strong>
                    </h3>
                    </div>
                    <p class="mb-1">{{ $product->short_description }}.</p>
                    <small>{{ $product->company->name }}.</small>
                </a>


                @empty
                    <p>No products available.</p>
                @endforelse

            </div>

        </div>
    </div>
    <div class="card-footer">
        @if(!$products->isEmpty())

            <div class="text-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

@stop
