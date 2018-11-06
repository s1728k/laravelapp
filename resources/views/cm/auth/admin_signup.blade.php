@extends("cm.layouts.admin")

@section("content")
<h3 class="center-align">Admin Sign Up</h3>
	<div class="row">
		<div class="col s12 m4 offset-m4">
			<form method="POST" action="{{ route('c.auth.admin.signup.submit') }}" aria-label="{{ __('AdminRegister') }}">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="row">
                    <div class="input-field col s12">
                    	<i class="fa fa-user prefix "></i>
	                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus/>
	                    <label for="name">Profile Name</label>
	                    @if ($errors->has('name'))
	                        <span class="helper-text" data-error="wrong" data-success="right">
	                            <strong>{{ $errors->first('name') }}</strong>
	                        </span>
	                    @endif
	                </div>
                    <div class="input-field col s12">
                    	<i class="fa fa-envelope prefix"></i>
	                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required/>
	                    <label for="email">E-Mail Address</label>
	                    @if ($errors->has('email'))
	                        <span class="helper-text" data-error="wrong" data-success="right">
	                            <strong>{{ $errors->first('email') }}</strong>
	                        </span>
	                    @endif
	                </div>
                    <div class="input-field col s12">
                    	<i class="fa fa-key prefix"></i>
	                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required/>
	                    <label for="password">Password</label>
	                    @if ($errors->has('password'))
	                        <span class="helper-text" data-error="wrong" data-success="right">
	                            <strong>{{ $errors->first('password') }}</strong>
	                        </span>
	                    @endif
	                </div>
                    <div class="input-field col s11 offset-s1">
	                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required/>
	                    <label for="password-confirm">Confirm Password</label>
	                </div>
                    <div class="input-field col s12">
                    	<i class="fa fa-user prefix "></i>
                        <select class="form-control" name="role" id="role">
                            <option selected="selected">Moderator</option>
                        </select>
                        <label for="role">Role</label>
                    </div>
                    <div class="input-field col s11 offset-s1">
                    	<button type="submit" class="waves-effect waves-light btn blue darken-2">
	                    	<i class="fa fa-user left"></i>
	                        {{ __('Register') }}
	                    </button>
	                </div>
	            </div>
	        </form>
		</div>
	</div>
@endsection