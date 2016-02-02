@extends('layouts.app')

@section('content')
{!! Form::model($user, [
    'method' => 'PATCH',
    'url' => ['users', $user->id],
    'class' => 'form-horizontal'
]) !!}

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{!! trans('users.heading_edit') !!}</div>
                <div class="panel-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                    @endif

                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', trans('users.name').': ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                        {!! Form::label('email', trans('users.email').': ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
                        {!! Form::label('password', trans('users.password').': ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::password('password', ['class' => 'form-control']) !!}
                            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    
                    @if(!$user->superuser && Gate::allows('manage-roles', $user))
                    <div class="form-group {{ $errors->has('roles') ? 'has-error' : ''}}">
                        {!! Form::label('roles', trans('users.roles').': ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            @foreach(config('acl.roles') as $role => $permissions)
                                <div class="checkbox">
                                    <label>{!! Form::checkbox('roles[]', $role, $user->hasRole($role), array('class' => 'checkbox-role', 'data-target' => 'permissions-'.$role)) !!} <span>{{ trans('users.roles.'.$role) }}</span></label>
                                </div>
                                @if($permissions)
                                    <div class="col-md-offset-1" id="permissions-{{$role}}">
                                    @foreach($permissions as $permission)
                                        <div class="checkbox">
                                            <label>{!! Form::checkbox('permissions[]', $permission, $user->hasPermission($permission, false)) !!} <span>{{ trans('users.permission.'.$permission) }}</span></label>
                                        </div>
                                    @endforeach
                                    </div>
                                @endif
                            @endforeach
                            {!! $errors->first('roles', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    @endif
                    
                    @can('createsuperuser', $user)
                    <div class="form-group {{ $errors->has('superuser') ? 'has-error' : ''}}">
                        {!! Form::label('superuser', trans('users.superuser').': ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            <div class="checkbox">
                                <label>{!! Form::radio('superuser', '1') !!} <span>Yes</span></label>
                            </div>
                            <div class="checkbox">
                                <label>{!! Form::radio('superuser', '0', true) !!} <span>No</span></label>
                            </div>
                            {!! $errors->first('superuser', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    @endcan


                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <a href="{{ url('users') }}" class="btn btn-default">{!! trans('users.button_cancel') !!}</a>
                            &nbsp;
                            {!! Form::submit(trans('users.button_edit'), ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}
@endsection