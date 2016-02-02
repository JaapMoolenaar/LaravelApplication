@extends('layouts.app')

@section('content')
{!! Form::open([
    'method'=>'DELETE',
    'url' => ['users', $user->id],
    'style' => 'display:inline'
]) !!}

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{!! trans('users.heading_predelete', ['name' => 'User', 'name_plural' => 'Users']) !!}</div>
                <div class="panel-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                    @endif

                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        <div class="col-sm-12">
                            <div class="checkbox">
                                <label>{!! Form::checkbox('confirm', '1') !!} <span>{!! trans('users.label_confirmdelete', ['name' => 'User', 'name_plural' => 'Users']) !!}</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <a href="{{ url('users') }}" class="btn btn-default">{!! trans('users.button_cancel', ['name' => 'User', 'name_plural' => 'Users']) !!}</a>
                            &nbsp;
                            {!! Form::submit(trans('users.button_delete', ['name' => 'User', 'name_plural' => 'Users']), ['class' => 'btn btn-danger']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}
@endsection