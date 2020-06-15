@extends('layouts.app')

@section('content')
    <h1 class="text-sm">{{ $user->firstname }} {{ $user->lastname }}</h1>

    <p>{{ $password }}</p>

    <p>{{ env('APP_URL') }}
@endsection