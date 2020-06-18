@extends('layouts.app')

@section('content')

    <div class="w-full lg:w-9/12 py-16 mx-auto">
        <h1 class="text-3xl mb-4 font-bold">
            Reports
        </h1>

        @if (auth()->user()->hasRole(['administrator']))
            <h2 class="text-xl mb-2">
                Administrator reports
            </h2>
            <ul>
                <li>
                    <a href="reports/users/download">
                        User report
                    </a>
                </li>
            </ul>
        @endif

        <h2 class="text-xl mb-4 mt-6">
            Statement of training
        </h2>

        <p>SoT user table to go here.</p>
    </div>

@endsection