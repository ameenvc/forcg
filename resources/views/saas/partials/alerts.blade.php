@if(session('status'))
    <div class="alert alert-success d-flex align-items-start saas-alert" role="status"><i class="fa fa-check-circle mt-1 mr-3" aria-hidden="true"></i><div>{{ session('status') }}</div></div>
@endif
@if($errors->any())
    <div class="alert alert-danger saas-alert" role="alert"><strong>Please check the following:</strong><ul class="mb-0 pl-3 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
