@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">{!! trans('users.heading_show') !!}</div>
            <div class="panel-body">
                <p><a href="{{ url('users') }}" class="btn btn-default">{!! trans('users.button_goback') !!}</a></p>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <tr><th>Name</th><td>{{ $user->name }}</td></tr><tr><th>Email</th><td>{{ $user->email }}</td></tr><tr><th>Password</th><td>{{ $user->password }}</td></tr><tr><th>Superuser</th><td>{{ $user->superuser }}</td></tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection