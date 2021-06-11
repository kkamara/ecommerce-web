@extends('layouts.master')

@section('content')


<div class="card">

    <div class="card-header">
        <form class='form-inline' action="" method='GET'>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fa fa-sort" aria-hidden="true"></i>
                        </div>
                    </div>
                    <select name="sort_by" class='form-control'>
                        <option value="">Sort by</option>
                        <option @if(isset($input['sort_by']) && $input['sort_by'] == 'pop') selected @endif value="pop">Most Popular</option>
                        <option @if(isset($input['sort_by']) && $input['sort_by'] == 'top') selected @endif value="top">Top Rated</option>
                        <option @if(isset($input['sort_by']) && $input['sort_by'] == 'low') selected @endif value="low">Lowest Price</option>
                        <option @if(isset($input['sort_by']) && $input['sort_by'] == 'hig') selected @endif value="hig">Highest Price</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">£</div>
                    </div>
                    <input type="number" min="0.01" step="0.01" max="2500" name="min_price" class='form-control' placeholder='Min' value='{{ $input['min_price'] ?? '' }}'>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">£</div>
                    </div>
                    <input type="number" min="0.01" step="0.01" max="2500" name="max_price" class='form-control' placeholder='Max' value='{{ $input['max_price'] ?? '' }}'>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                    </div>
                    <input name='query' type="text" class='form-control' placeholder="Search..." value='{{ $input['query'] ?? '' }}'>
                </div>
            </div>

            <div class="form-group">
                <input class='form-control btn btn-success' type="submit" value="Submit">
            </div>
        </form>
    </div>
    <div class="card-body">

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
                    <div class="float-right">
                        Average Rating: @if($product->review !== '0.00') {{ $product->review }} @else None @endif
                    </div>
                </a>


                @empty
                    <p>There are no products currently available.</p>
                @endforelse

            </div>

        </div>
    </div>
    <div class="card-footer">
        @if(!$products->isEmpty())

            <div class="float-left">
                {{ $products->links() }}
            </div>

            <div class="float-right">
                Showing {{ $products->count() }} of {{ $products->total() }}
            </div>
        @endif
    </div>
</div>

@stop
