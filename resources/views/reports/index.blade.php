@extends('layouts.app')

@section('content')

    <div class="w-full lg:w-9/12 py-16 mx-auto">
        <h1 class="text-3xl mb-4 font-bold">
            Reports
        </h1>

        <ul>
            <li>
                <a href="reports/users/download">
                    User report
                </a>
            </li>

            <li>
                <a href="reports/test/download">
                    test report
                </a>
            </li>
        </ul>
    </div>

@endsection