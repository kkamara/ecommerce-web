@extends('layouts.master')

@section('content')
@include('layouts.errors')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('companyProductUpdate', [$product->company->slug, $product->id]) }}" method="POST" class="card" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="card-header">
                <div class="lead">
                    {{ $title }}
                </div>
            </div>
            <div class="card-body">
                <div class="card-text">

                    <div class="form-group">
                        <label>
                            Listing Name:
                            <input 
                                dusk="product-name"
                                type="text" 
                                class='form-control' 
                                name='name' 
                                value="{{ $product->name }}"
                            />
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Cost:
                            <input type="number" step=".01" name='cost' class='form-control' value="{{ str_replace('£', '', $product->formatted_cost) }}">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Shippable:
                            <select name="shippable" class='form-control'>
                                <option value="0">No</option>
                                <option @if($product->shippable)) selected @endif value="1">Yes</option>
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Free Delivery:
                            <select name="free_delivery" class='form-control'>
                                <option value="0">No</option>
                                <option @if($product->free_delivery)) selected @endif value="1">Yes</option>
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            Use default image for this product?
                            <select name="use_default_image" class='form-control'>
                                <option value="0">No</option>
                                <option @if($product->usingDefaultImage())) selected @endif value="1">Yes</option>
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Product image:
                            <input type="file" class='form-control' name='image'>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Short Description:
                            <textarea name="short_description" class='form-control' cols="150" rows="2">{{ $product->short_description }}</textarea>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Long Description:
                            <textarea name="long_description" class='form-control' cols="150" rows="2">{{ $product->long_description }}</textarea>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Product details:
                            <textarea name="product_details" class='form-control' cols="150" rows="2">{{ $product->product_details }}</textarea>
                        </label>
                    </div>

                </div>
            </div>
            <div class="card-footer">

                <div class="form-group pull-left">
                    <a href='{{ route('companyProductHome', $product->company->slug) }}' class='btn btn-secondary'>Back</a>
                </div>

                <div class="form-group pull-right">
                    <input dusk="submit-btn" type="submit" class='btn btn-primary'>
                </div>

            </div>
        </form>
    </div>
</div>

@stop