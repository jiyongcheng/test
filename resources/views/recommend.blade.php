@extends('layout')
@section('body')
    <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Home</a>
                <a class="navbar-brand" href="/recipe">Input Data</a>
            </div>
        </div>
    </nav>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Recommend Recipe</h3>
                </div>
                <div class="panel-body">
                    {{$recipe}}
                </div>
            </div>
        </div>
    </div>
@endsection