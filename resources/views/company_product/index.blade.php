@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <div class="lead">{{ $title }}</div>
                </div>

                <div class="float-right">
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
                            <input class='form-control btn btn-success' type="submit" value="Submit" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="card-text">

                    @if(! $companyProducts->isEmpty())
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Image</th>
                                    <th>Short Description</th>
                                    <th>Cost</th>
                                    <th>Shippable</th>
                                    <th>Free Delivery</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companyProducts as $companyProduct)
                                    <tr>
                                        <td>
                                            <a href="{{ route('productShow', $companyProduct->id) }}">{{ $companyProduct->name }}</a>
                                        </td>
                                        <td><img style='max-height:100px' src="{{ $companyProduct->image_path }}" class='img-responsive btn btn-lg'></td>
                                        <td>{{ $companyProduct->short_description }}</td>
                                        <td>{{ $companyProduct->formatted_cost }}</td>
                                        <td>
                                            @if($companyProduct->shippable)
                                                Yes
                                            @else
                                                No
                                            @endif
                                        </td>
                                        <td>
                                            @if($companyProduct->free_delivery)
                                                Yes
                                            @else
                                                No
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        You have no products available at the moment.
                    @endif
                </div>
            </div>
            <div class="card-footer">
                @if(! $companyProducts->isEmpty())
                    {{ $companyProducts->links() }}
                @endif
            </div>
        </div>

    </div>
</div>

@stop