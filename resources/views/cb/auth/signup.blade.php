@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 text-center">
			<h3>Sign Up</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form method="POST" action="{{ url('register') }}" aria-label="{{ __('Register') }}">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="form-group row">
	                <div class="col-md-4">
	                    <label for="name">Profile Name</label>
	                </div>
	                <div class="col-md-6">
	                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus/>
	                    @if ($errors->has('name'))
	                        <span class="invalid-feedback" role="alert">
	                            <strong>{{ $errors->first('name') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>
				<div class="form-group row">
	                <div class="col-md-4">
	                    <label for="email">E-Mail Address</label>
	                </div>
	                <div class="col-md-6">
	                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required/>
	                    @if ($errors->has('email'))
	                        <span class="invalid-feedback" role="alert">
	                            <strong>{{ $errors->first('email') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>
				<div class="form-group row">
	                <div class="col-md-4">
	                    <label for="password">Password</label>
	                </div>
	                <div class="col-md-6">
	                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required/>
	                    @if ($errors->has('password'))
	                        <span class="invalid-feedback" role="alert">
	                            <strong>{{ $errors->first('password') }}</strong>
	                        </span>
	                    @endif
	                </div>
	            </div>

	            <div class="form-group row">
	                <div class="col-md-4">
	                    <label for="password-confirm">Confirm Password</label>
	                </div>
	                <div class="col-md-6">
	                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required/>
	                </div>
	            </div>
	            <div class="form-group row">
	                <div class="col-md-4"></div>
	                <div class="col-md-6">
	                    <button type="submit" class="btn btn-primary">
	                        {{ __('Register') }}
	                    </button>
	                </div>
	            </div>
	        </form>
		</div>
	</div>
</div>
@endsection