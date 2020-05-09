@extends('web::layouts.grids.12')

@section('title', trans_choice('winkill::winkill.settings', 0))
@section('page_header', trans_choice('winkill::winkill.settings', 0))

@section('full')

  <div class="row">

    <div class="col-md-3">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Common</h3>
        </div>
        <div class="panel-body">
          <form method="post" id="winkill-setup">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="prefix-format">{{ trans('winkill::winkill.maxprice') }}</label>
              @if(setting('winkill.max_price', true) == '')
                <input type="number" id="max-price" name="max-price" class="form-control" value="10000000" />
              @else
                <input type="number" id="max-price" name="max-price" class="form-control" value="{{ setting('winkill.max_price', true) }}" />
              @endif
            </div>
          </form>
        </div>
        <div class="panel-footer clearfix">
          <button type="submit" class="btn btn-success pull-right" form="winkill-setup">{{ trans('winkill::winkill.save') }}</button>
        </div>
      </div>

    </div>


  </div>

@stop

@push('javascript')

@endpush