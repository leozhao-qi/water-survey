@extends('layouts.app')

@section('content')

    <user-package 
        :user-id="{{ $user->id }}"
        :userpackage-id="{{ $package->id }}"
    />

@endsection