@if(session('flashSuccess'))
    <div class="alert alert-success text-center">
        <div class="panel-heading">{{ session('flashSuccess') }}</div>
    </div>
@endif

@if(session('flashWarning'))
    <div class="alert alert-warning text-center">
        <div class="panel-heading">{{ session('flashWarning') }}</div>
    </div>
@endif

@if(session('flashDanger'))
    <div class="alert alert-danger text-center">
        <div class="panel-heading">{{ session('flashDanger') }}</div>
    </div>
@endif
