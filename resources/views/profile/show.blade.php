@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">{!! trans('profile.heading_show') !!}</div>
            <div class="panel-body">
                <p><a href="{{ url('profile') }}" class="btn btn-default">{!! trans('profile.button_goback') !!}</a></p>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <tr><th>Name</th><td>{{ $profile->name }}</td></tr><tr><th>Email</th><td>{{ $profile->email }}</td></tr><tr><th>Password</th><td>{{ $profile->password }}</td></tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection