@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">{!! trans('users.heading_index') !!}</div>
            <div class="panel-body">
                @can('create-user')
                    <p><a href="{{ url('users/create') }}" class="btn btn-primary">{!! trans('users.button_gocreate') !!}</a></p>
                @endcan

                @include('flash::message')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ trans('users.name') }}</th>
                                <th>{{ trans('users.email') }}</th>
                                <th>{!! trans('users.tableheading_actions') !!}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $item)
                            <tr>
                                <td><a href="{{ url('users', $item->id) }}">{{ $item->name }}</a>@if($item->superuser)<i title="Super admin" class="fa fa-thumbs-o-up pull-right" style="margin-top: 3px"></i> @endif</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    @can('update-user', $item)
                                        <a href="{{ url('users/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs">{!! trans('users.button_goedit') !!}</a>
                                    @endcan
                                    @can('delete-user', $item)
                                        &nbsp;
                                        <a href="{{ url('users/' . $item->id . '/predelete') }}" class="btn btn-danger btn-xs">{!! trans('users.button_godelete') !!}</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pagination"> {!! $users->render() !!} </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
