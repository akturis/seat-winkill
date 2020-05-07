<a class="pull-center" id="ship_count" data-id="{{ $row->typeID }}" data-name="{{ $row->typeName }}" data-user="{{ $row->character_id }}" href="#">
<button type="button" class="btn btn-outline-{{empty($btn)?'secondary':$btn}}">{{ $row->operations}}</button>
</a>
