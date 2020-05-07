@if(isset($character_id ))
<a class="pull-left" id="paps_count" data-id="{{ $character_id }}" href="#">
@endif
<button type="button" class="btn btn-success">{{ $paps }}</button>
@if(isset($character_id ))
</a>
@endif
