@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 text-center">
      <h3>Reset Password</h3>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <form method="POST" action="{{ route('c.auth.password.reset.submit', ['rtype' => $rtype, 'id' => $id]) }}" aria-label="{{ __('Reset Password') }}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-group row">
            <div class="col-md-4">
                <label for="password">New Password</label>
            </div>
            <div class="col-md-6">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" name="password" required>
                @if ($errors->has('password'))
                    <span class="help-block">
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
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-4"></div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection