@if (isset($group_id))
  <i class="pull-right" data-toggle="tooltip" title="" data-original-title="{{ $row->groupName }}">
    {{ $row->groupName }}
  </i>
@endif