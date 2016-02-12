@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        {!!
                        ControlGroup::generate(
                            Form::label('email', 'E-Mail Address'),
                            Form::email('email'),
                            null,
                            4,
                            6
                        )->withValidation('email')
                        !!}
                        
                        {!!
                        ControlGroup::generate(
                            Form::label('password', 'Password'),
                            Form::password('password'),
                            null,
                            4,
                            6
                        )->withValidation('password')
                        !!}
                        
                        {!!
                        ControlGroup::generate(
                            null,
                            Form::checkbox('remember')->withLabel('Remember Me'),
                            null,
                            4,
                            6
                        )->withValidation('remember')
                        !!}

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
