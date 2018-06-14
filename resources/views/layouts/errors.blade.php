@if(!empty($errors))
<ul class="list-group text-center errors">
    @foreach($errors as $error)
    <li class="list-group-item-danger">{{ $error }}</li>
    @endforeach
</ul>
@endif
