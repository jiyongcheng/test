@extends('layout')
@section('body')
    <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Home</a>
            </div>
        </div>
    </nav>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="/recipe/find" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="fridge_ingredients">Fridge Ingredients</label>
            <input type="file" id="fridge_ingredients" name="fridge_ingredients" class="form-control" required/>
        </div>
        <div class="form-group">
            <label for="recipes">Recipes</label>
            <textarea id="recipes" name="recipes" class="form-control" cols="50" rows="10" placeholder="please input json string" required></textarea>
            <p>example json:</p>
            <p>
                [{
                "name": "grilled cheese on toast", "ingredients": [
                { "item":"bread", "amount":"2", "unit":"slices"},
                { "item":"cheese", "amount":"2", "unit":"slices"} ]
                },{
                "name": "salad sandwich", "ingredients": [
                { "item":"bread", "amount":"2", "unit":"slices"},
                { "item":"mixed salad", "amount":"100", "unit":"grams"} ]
                }]
            </p>
        </div>
        <button type="submit" class="btn btn-primary">Find Recipe</button>
    </form>
@endsection