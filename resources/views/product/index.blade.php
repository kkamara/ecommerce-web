@extends('layouts.master')

@section('content')

<div class="">
    Showing {{ $products->count() }} of {{ $products->total() }}
</div>

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
                <div class="container">

                    @forelse($products as $key => $product)

                        <x-product-card :key="$key" :product="$product" />

                    @empty
                        <p>There are no products currently available.</p>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
    <div class="card-footer">
        @if(!$products->isEmpty())

            <div class="float-left">
                {{ $products->links() }}
            </div>

        @endif
    </div>
</div>

@stop
