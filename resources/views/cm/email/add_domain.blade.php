@extends("cm.layouts.app")

@section("content")
<div class="container-fluid">
	<div id="alrt">
		@isset($domain)
		@if($domain->verified == 'done')
		<div class="alert alert-success text-center"><strong>{{$domain->name}}</strong> Verified already!</div>
		@endif
		@endisset
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<caption class="">
			@isset($domain) @if($domain->verified != 'done') Verify ownership of the domain <strong>{{$domain->name}}</strong> @endif @else Add New Domain @endisset
			<div class="input-group" style="float:right;">
				<a class="btn btn-default" href="{{route('c.email.list.view')}}">Back</a></div></caption>
		</div>
	</div>
	@isset($domain)
	@if($domain->verified != 'done')
	<div class="row">
		<div class="col-md-12 text-center">
			<h3>Method 1: Add TXT record to your domain DNS</h3>
			<p>add TXT record to your domain with </p>
			<p>name : <strong>{{$domain->name}}</strong>, value : <strong>{{$domain->verified}}</strong></p>
			<p>this method may take one day as TXT value in your domain takes time to reflect.</p>
			<p>when you are ready to verify click verify domain button</p>
			<button class="btn btn-primary" onclick="verifyTXT()">Verify Domain</button>
			<script>
				function verifyTXT(){
					$.post("{{route('c.email.get.txt')}}", {"id":"{{$domain->id}}", "_token":"{{csrf_token()}}"}, function(data){
						if(data['status'] == 'success'){
							var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Domain {{$domain->name}} Verified successfully!</div>';
							$('#alrt').html(ht);
						}else{
							console.log(data);
							var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Domain {{$domain->name}} Not Verified!</div>';
							$('#alrt').html(ht);
						}
					});
				}
			</script>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
			<h3>Method 2: Add page to your website</h3>
			<p>add page to your website with</p>
			<p>route name : <strong>http://{{$domain->name}}/honeyweb-domain-verification</strong>, page contents : <strong>{{$domain->verified}}</strong></p>
			<p>this method is instant and quick.</p>
			<p>when you are ready to verify click verify domain button</p>
			<button class="btn btn-primary" onclick="verifyPageContent()">Verify Domain</button>
			<script>
				function verifyPageContent(){
					$.post("{{route('c.email.get.page')}}", {"id":"{{$domain->id}}", "_token":"{{csrf_token()}}"}, function(data){
						if(data['status'] == 'success'){
							var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Domain {{$domain->name}} Verified successfully!</div>';
							$('#alrt').html(ht);
						}else{
							console.log(data);
							var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Domain {{$domain->name}} Not Verified!</div>';
							$('#alrt').html(ht);
						}
					});
				}
			</script>
		</div>
	</div>
	@endif
	@else
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form method="post" action="{{route('c.email.new.domain.submit')}}" >
		        <input type="hidden" name="_token" value="{{csrf_token()}}" />

				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="name">Domain Name:</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="Domain Name">
						@if($errors->has('name'))
							<p style="color:red">{{$errors->first('name')}}</p> 
						@endif
					</div>			
				</div>

				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4"></div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary">Add New Domain</button>
					</div>			
				</div>
		    </form>
		</div>
	</div>
	@endisset
</div>
@endsection