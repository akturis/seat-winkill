@if (isset($type_id))
  <i class="pull-center" data-toggle="tooltip" title="" data-original-title="{{ $row->typeName }}">
    {!! img('type', $row->typeID, 128, ['class' => 'img-circle eve-icon small-icon'], false) !!} {{ $row->typeName }}
  </i>
@endif
