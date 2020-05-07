@extends('web::layouts.grids.4-8')

@section('title', trans('stats::stats.paps'))
@section('page_header', trans('stats::stats.paps'))

@push('head')
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>

@endpush

@section('left')
<div class="box box-widget widget-user-2" id="paps_box">
    <div class="col-md-12">
      <select class="form-control bg-aqua" style="width: 30%" id="period">
        <option selected disabled>Choose a period</option>
        <option value="0">All time</option>
        <option value="1">This week</option>
        <option value="2" selected="selected">This month</option>
        <option value="3">This year</option>
        <option value="4">Last 30 days</option>
        <option value="5">Last 90 days</option>
      </select>
    </div>
    <div class="widget-user-header bg-aqua">
        <h2 class="widget-user-username">
            <div class="container-fluid">
                <div class="row">
                  <div class="col-sm-4">Total</div>
                  <div class="col-sm-2" id="paps_total"></div>
                  <div class="col-sm-4">paps</div>
                </div>
            </div>
        </h2>
    </div>
    <div class="box-footer no-padding">
        <table class="table table-striped table-hover" id="paps" style="margin-top: 0 !important;">
            <thead class="bg-aqua">
                <tr>
                    <th>{{ trans('stats::stats.paps-group') }}</th>
                    <th>{{ trans('stats::stats.paps-ship') }}</th>
                    <th>{{ trans('stats::stats.name') }}</th>
                    <th>{{ trans('stats::stats.paps-count') }}</th>
                    <th>{{ trans('stats::stats.operation-count') }}</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align:right">Total:</th>
                    <th></th>
                </tr>
            </tfoot>
        <tbody></tbody>
        </table>
    </div>
</div>

@endsection
@section('right')
<div class="box box-widget widget-user-2" id="operations">
    <div class="widget-user-header bg-aqua">
        <div class="container-fluid">
            <div class="row">
              <h1><span class="col-sm-2" id="operation_count"></span></h1>
              <span class="col-sm-5 text-center" id="operation_ship"></span>
              <span class="col-sm-5 text-center" id="operation_character"></span>
            </div>
            <div class="row">
              <div class="col-sm-2"></div>
              <div class="col-sm-5 text-center" id="operation_ship_name"></div>
              <div class="col-sm-5 text-center" id="operation_character_name"></div>
            </div>
        </div>
    </div>
    <div class="box-footer no-padding">
        <table class="table table-striped table-hover" id="operations_list" style="margin-top: 0 !important;">
            <thead class="bg-aqua">
                <tr>
                    <th>{{ trans('calendar::seat.title') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.tags') }}</th>
                    <th>{{ trans('calendar::seat.paps') }}</th>
                    <th>{{ trans('calendar::seat.starts_in') }}</th>
                    <th class="hidden-xs">{{ trans('calendar::seat.fleet_commander') }}</th>
                    <th>{{ trans('calendar::seat.staging') }}</th>
                    <th class="hidden-xs"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection    
@push('javascript')
  @include('web::includes.javascript.id-to-name')

  <script type="application/javascript">
      var table;
      var operations;
      id = $('#period').find(":selected").val();
      $('#period').change(function () {
          id = $('#period').find(":selected").val();
          table.ajax.url( '{{ route("stats.paps.view") }}/?period='+id).load();
          $('#operations').hide();
      });

      $('#operations').hide();

      $('#corpspinner').change(function () {

          $('#indivmining').find('tbody').empty();
          id = $('#corpspinner').find(":selected").val();
          if (id > 0) {
            text = $('#corpspinner').find(":selected").text();
            table
                .column( 2 )
                .data()
                .search(text).draw();
          } else table
                .column( 2 )
                .data()
                .search('').draw();
      });

      $(document).ready(function () {
          $('#corpspinner').select2();
      });

      $('#alliancespinner').change(function () {
          id = $('#alliancespinner').find(":selected").val();
//              window.location.href = '/stats/alliance/' + id;
      });

      table = $('#paps').DataTable( {
          searching:false,
          responsive: true,
          "pageLength": 10,
          "ajax": {
            url: '{{ route("stats.paps.view") }}',
            data: function (d) {
            }
          },
          "columns": [
//            {data: 'corporation', name: 'corporation'},
            {data: 'group', name: 'group',visible:false},
            {data: 'ship', name: 'ship'},
            {data: 'name', name: 'name'},
            {data: 'paps', name: 'paps'},
            {data: 'operations', name: 'operations'},
          ],
            order: [[3, 'desc']],
            rowGroup: {
                dataSrc: ['group']
            },
        "drawCallback": function () {
                var api = this.api(), data;
            // Total over all pages
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return parseInt(a) + parseInt($(b).text());
                    }, 0 );
            // Update footer
                $( api.column( 3 ).footer() ).html(
                    total +' paps'
                );
                $('#paps_total').html(total);
                $("img").unveil(100);
                ids_to_names();
                $('[data-toggle="tooltip"]').tooltip();
              }
    } );
      $('#livepve').DataTable();
      
    $('#paps').on('click', '#ship_count', function () {
        s_id = $(this).data('id');
        u_id = $(this).data('user');
        $('#operations').show();
        $('#operation_ship').empty();
        $('#operation_character').empty();
        $('#operation_ship').html('<img src="https://images.evetech.net/types/'
                                    +$(this).data('id')
                                    +'/icon"></img>');
        $('#operation_ship_name').html('<span class="id-to-name" data-id="'
                                    +$(this).data('id')
                                    +'">'
                                    +'</span>');
        $('#operation_character').html('<img src="https://images.evetech.net/characters/'
                                    +$(this).data('user')
                                    +'/portrait?size=64"></img>');
        $('#operation_character_name').html('<span class="id-to-name" data-id="'
                                    +$(this).data('user')
                                    +'"></span>');
          operations = $('#operations_list').DataTable( {
              destroy: true,
              responsive: true,
              "pageLength": 25,
              "ajax": {
                url: "/stats/paps/operations/",
                data: function(d){
                    d.character_id = u_id;
                    d.ship_type_id = s_id;
                    d.period = $('#period').find(":selected").val();
                }
              },
              "columns": [
    //            {data: 'corporation', name: 'corporation'},
                {data: 'title', name: 'title'},
                {data: 'tag', name: 'tag'},
                {data: 'paps', name: 'paps'},
                {data: 'start_at', name: 'start_at'},
                {data: 'fc', name: 'fc'},
                {data: 'staging_sys', name: 'staging_sys'},
              ],
              order: [[3, 'desc']],
              "drawCallback": function () {
                    total = this.api().data().count();
                    $('#operation_count').html(total+' times');
                    $("img").unveil(100);
                    ids_to_names();
                    $('[data-toggle="tooltip"]').tooltip();
                  }
        } );
    });

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
