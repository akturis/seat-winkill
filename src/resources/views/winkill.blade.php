@extends('web::layouts.grids.12')

@section('title', 'Win Kill')
@section('page_header', 'Win Kill')

@push('head')
  <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 
  
@endpush

@inject('CharacterInfo', 'Seat\Eveapi\Models\Character\CharacterInfo')
@inject('User', 'Seat\Web\Models\User')

@section('full')

@if(isset($error))
    <div class="alert alert-danger">{{ $error }}
    <a href="{{ route('notifications.integrations.list') }}">Link</a>
    </div>
@endif

    <form id="formkill" method="POST" action="{{ route('winkill.view') }}">
      <div class="form-group">
          {{ csrf_field() }}
        <label for="exampleInputName2" class="col-sm-2 control-label">Killmail</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="killmail" id="killmail" placeholder="https://esi.evetech.net/...kill...">
        </div>
      </div>
      <button type="submit" class="btn btn-default">Win!</button>
    </form>

@if (isset($win_items))
<div class="table-responsive">
    <table class="table">
    <thead>
        <th><a target="_blank" href="https://zkillboard.com/kill/{{$killmail_id}}">Winners for https://zkillboard.com/kill/{{$killmail_id}}</a></th>
    </thead>
    <tbody>
@foreach($win_items as $item)
        <tr>
            <td>
                @include('web::partials.character', ['character' => $CharacterInfo::find($item['character']['character_id']) ?: $item['character']['character_id'], 'character_id' => $item['character']['character_id']])
            </td>
            <td>
                <a target="_blank" href="https://zkillboard.com/item/{{$item['item']['item_id']}}"><span class="id-to-name" data-id="{{ $item['item']['item_id'] }}" >
                    {{ trans('web::seat.unknown') }}
                </span></a>
            </td>
            <td>{{ number_format($item['item']['price'],2) }}</td>
        </tr>
@endforeach
    </tbody>
    </table>
</div>
@endif

@endsection

@push('javascript')
  @include('web::includes.javascript.id-to-name')
@endpush
