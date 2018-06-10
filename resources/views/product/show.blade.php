@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <table class="table table-striped">
                <tbody>
                    <tr class='text-center'>
                        <th scope='row' colspan='2'>
                            <img style='max-height:100px' src="{{ $product->image_path }}" class='img-responsive'/>
                            <h4>
                                {{ $product->name }}
                            </h4>
                        </th>
                    </tr>
                    <tr>
                        <td scope='row'>Description</td>
                        <td>
                            {{ $product->short_description }}
                            <br/>
                            <br/>
                            {{ $product->long_description }}
                        </td>
                    </tr>
                    <tr>
                        <td scope='row'>Product Details</td>
                        <td>
                            {{ $product->product_details }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <h3>
                        {{ $product->formatted_cost }}
                    </h3>
                    <small>
                        @if($product->shippable)
                            This item is shippable
                        @else
                            This item is <strong>not</strong> shippable
                        @endif
                    </small>
                    <br/>
                    @if($product->free_delivery)
                        <small>Free Delivery</small>
                    @endif
                </li>
                <li class="list-group-item">
                    <a href='{{ route('productAdd', $product->id) }}' class='btn btn-primary'>
                        Add to cart
                    </a>
                </li>
            </ul>
        </div>
    </div>
@stop
