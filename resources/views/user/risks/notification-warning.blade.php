@if(Session::has('risk_notification_warning'))
    <div class="alert alert-warning" role="alert">
        <strong>Email notification unavailable.</strong>
        {{ Session::get('risk_notification_warning') }}
    </div>
@endif
