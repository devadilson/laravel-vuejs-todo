@extends('layouts.app')

@section('content')
<div class="container">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <meta id="token" name="token" content="{{ csrf_token() }}">

        <title>Laravel</title>        
    </head>
        <div class="container" id="app">
            <tasks token="{{ csrf_token() }}"></tasks>
        </div>
</div>
@endsection