@if(session('flashSuccess'))
    <div class="alert alert-success text-center">
        <div class="panel-heading">{{ session('flashSuccess') }}</div>
    </div>
@endif