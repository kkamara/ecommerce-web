@if(!empty($errors))
<ul class="list-group errors" style='list-style-type:none;margin:0px 0px 10px 0px;'>
    @foreach($errors as $error)
    <li style="padding:5px 0px 5px 10px;" class="list-group-item-danger">{{ $error }}</li>
    @endforeach
</ul>
@endif
