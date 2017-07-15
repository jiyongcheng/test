@extends('layout')
@section('body')
    <div class="vertical-center">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-4 col-md-offset-2">
                    <ul class="top-features">
                        <a href="{{route('pets.store')}}" class="btn-primary btn h2">Test 1</a>
                        <h6>RESTful Web Service Test</h6>
                    </ul>
                </div>

                <div class="col-md-4">
                    <ul class="top-features">
                        <a href="{{route('recipe.index')}}" class="btn-primary btn h2">Test 2</a>
                        <h6>PHP Coding Challenge</h6>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection