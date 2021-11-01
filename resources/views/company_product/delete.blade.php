@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('companyProductDestroy', [$product->company->slug, $product->id]) }}" method="POST" class="card">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}

            <div class="card-header">
                <div class="lead">
                    {{ $title }}
                </div>
            </div>
            <div class="card-body">
                <div class="card-text text-center">
                    <div class="form-group">
                        <label>Are you sure you want to delete this item from your product listings?
                            <select 
                                dusk="choice" 
                                name="choice" 
                                class='form-control'
                            >
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-footer">

                <div class="form-group pull-left">
                    <a href='{{ route('productShow', $product->id) }}' class='btn btn-secondary'>Back</a>
                </div>

                <div class="form-group pull-right">
                    <input dusk="submit-btn" type="submit" class='btn btn-primary'>
                </div>

            </div>
        </form>
    </div>
</div>

@stop