@extends('blog1::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('blog1.name') !!}
    </p>
@endsection
