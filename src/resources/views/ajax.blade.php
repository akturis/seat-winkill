@extends('web::layouts.grids.12')

@section('title', trans('stats::stats.summary'))
@section('page_header', trans('stats::stats.summary'))

@section('full')
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right bg-gray">
      <li><a href="#tab2" data-toggle="tab">{{ trans('stats::stats.summary-pvp') }}</a></li>
      <li class="active"><a href="#tab1" data-toggle="tab">{{ trans('stats::stats.summary-char-mission') }}</a></li>
      <li class="pull-left header">
        <i class="fa fa-line-chart"></i> {{ trans('stats::stats.summary-live') }}
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab1">
        <div class="col-md-12">
          <select class="form-control" style="width: 25%" id="corpspinner">
            <option selected disabled>{{ trans('stats::stats.choose-corp') }}</option>
            <option value="0">{{ trans('stats::stats.all-corps') }}</option>
          </select>
        </div>
        <table class="table table-striped" id='livenumbers'>
          <thead>
          <tr>
            <th>{{ trans('stats::stats.name') }}</th>
            <th>{{ trans('stats::stats.main') }}</th>
            <th>{{ trans('stats::stats.corp') }}</th>
            <th>{{ trans('stats::stats.mission-corp-reward') }}</th>
            <th>{{ trans('stats::stats.mission-char-reward') }}</th>
            <th title="{{ trans('stats::stats.mission-lp-desc') }}">{{ trans('stats::stats.mission-lp') }}</th>
            <th>{{ trans('stats::stats.mission-count') }}</th>
            <th>{{ trans('stats::stats.kill-count') }}</th>
            <th>{{ trans('stats::stats.paps-count') }}</th>
          </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

@endsection

@push('javascript')
  @include('web::includes.javascript.id-to-name')
  <script type="application/javascript">
      table = $('#indivmining').DataTable({
          paging: false,
      });

      $('#corpspinner').change(function () {

          $('#indivmining').find('tbody').empty();
          id = $('#corpspinner').find(":selected").val();
          if (id > 0) {
            text = $('#corpspinner').find(":selected").text();
            var table =  $('#livenumbers').DataTable();
            var filteredData = table
                .column( 3 )
                .data()
                .filter( function ( value, index ) {
                    return value === text ? true : false;
                } );
            //table.clear();
            filteredData.draw();
          }
      });

      $(document).ready(function () {
          $('#corpspinner').select2();
      });

      $('#alliancespinner').change(function () {
          id = $('#alliancespinner').find(":selected").val();
//              window.location.href = '/stats/alliance/' + id;
      });

      $('#livenumbers').DataTable( {
          pageLength: 50,
          ajax: {
            url: '{{ route("stats.view.ajax2") }}'
          },
          columns: [
            {data: 'name', name: 'name'},
            {data: 'main_character', name: 'main_character'},
            {data: 'corporation_name', name: 'corporation_name'},
            {data: 'bounties_format', name: 'bounties_format',type: 'formatted-num'},
            {data: 'bounties_char_format', name: 'bounties_char_format',type: 'formatted-num'},
            {data: 'lp', name: 'lp',type: 'formatted-num'},
            {data: 'missions', name: 'missions'},
            {data: 'kills', name: 'kills'},
            {data: 'paps', name: 'paps'}
          ],
        "order": [[ 3, "desc" ]],
        "drawCallback": function () {
                $("img").unveil(100);
                ids_to_names();
                $('[data-toggle="tooltip"]').tooltip();
              }
    } );
      $('#livepve').DataTable();

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
	"formatted-num-pre": function ( a ) {
		a = (a === "-" || a === "") ? 0 : a.replace( /[^\d\-\.]/g, "" );
		return parseFloat( a );
	},

	"formatted-num-asc": function ( a, b ) {
		return a - b;
	},

	"formatted-num-desc": function ( a, b ) {
		return b - a;
	}
} );

  </script>
@endpush
