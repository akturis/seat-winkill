@extends('web::layouts.grids.4-4-4')

@section('title', trans('stats::stats.paps'))
@section('page_header', trans('stats::stats.paps'))

@push('head')
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endpush

@section('left')
<div class="box box-widget widget-user-2" id="paps_box">
    <div class="container-fluid bg-aqua">
        <div class="row">
          <div class="col-sm-5">
              <select class="form-control bg-aqua" style="width: 100%" id="period">
                <option selected disabled>Choose a period</option>
                <option value="0">All time</option>
                <option value="1">This week</option>
                <option value="2">This month</option>
                <option value="3">This year</option>
                <option value="4" selected="selected">Last 30 days</option>
                <option value="5">Last 90 days</option>
              </select>
          </div>
    
          <div class="col-sm-5">
              <select class="selectpicker show-menu-arrow" multiple data-width="100%" style="width: 50%" id="tags" data-style="bg-aqua" data-done-button="true" title="Select tags">
        @foreach($tags as $tag)
                <option value="{{ $tag->id }}"> {{ $tag->name }}</option>
        @endforeach
              </select>
          </div>
          <div class="col-sm-2">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="main" title="Main characters" checked>
                <label class="form-check-label" for="main" >Main</label>
              </div>
          </div>
        </div>
    </div>
    <div class="box-footer no-padding">
        <table class="table table-striped table-hover" id="paps" style="margin-top: 0 !important;">
            <thead class="bg-aqua">
                <tr>
                    <th>{{ trans('stats::stats.paps-group') }}</th>
                    <th>{{ trans('stats::stats.name') }}</th>
                    <th>{{ trans('stats::stats.tag') }}</th>
                    <th>{{ trans('stats::stats.paps-count') }}</th>
                </tr>
            </thead>
        <tbody></tbody>
        </table>
    </div>
</div>

@endsection
@section('center')
<div class="box box-widget widget-user-2" id="pap_box">
    <div class="widget-user-header bg-aqua">
        <div class="container-fluid">
            <div class="row">
              <h1> </h1>
            </div>
        </div>
    </div>
    <div class="box-footer no-padding">
        <table class="table table-striped table-hover" id="paps_character" style="margin-top: 0 !important;">
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
              <span class="col-sm-2" id="operation_count"><h1></h1></span>
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

    $(document).ready(function(){
       $('.dataTables_filter .input-sm').css({"width":"70%"});
    });

      $('#operations').hide();
      $('#pap_box').hide();

      id = $('#period').find(":selected").val();
      $('#period').change(function () {
          id = $('#period').find(":selected").val();
          table.ajax.url( '/stats/summary/').load();
          $('#operations').hide();
          $('#pap_box').hide();
      });

    $("#main").click(function(){ 
      c_id = $("#main").is(':checked')?true:false;
      m_id = $("#main").is(':checked')?false:true;
      table.column( 0 ).visible( c_id ).column( 1 ).visible( m_id );
      table.ajax.url( '/stats/summary/').load();
    });

      $('#tags').change(function () {
          ids = $('#tags').val();
          setTimeout(function(){
              table.ajax.url( '/stats/summary/').load();
          }, 300);
          $('#operations').hide();
          $('#pap_box').hide();
      });

      $(document).ready(function () {
          $('#corpspinner').select2();
      });

      table = $('#paps').DataTable( {
          searching: true,
          responsive: true,
          processing: true,
          serverside: true,
          "pageLength": 10,
          "ajax": {
            url: '/stats/summary/',
            data: function (d) {
                d.period = $('#period').find(":selected").val();
                d.main = $("#main").is(':checked')?1:0;
                d.tag = $("#tags").val();
            }
          },
          "columns": [
//            {data: 'corporation', name: 'corporation'},
            {data: 'main_character', name: 'main_character'},
            {data: 'name', name: 'name',visible:false},
            {data: 'tag', name: 'tag', orderable: false},
            {data: 'paps', name: 'paps'},
          ],
            order: [[3, 'desc']],
          "search": {
            "addClass": 'col-xs-4'
          },            
/*            rowGroup: {
                startRender: null,
                endRender: function ( rows, group ) {
                    var total = rows
                        .data()
                        .pluck('paps')
                        .reduce( function (a, b) {
                            return a + parseFloat(b.replace(/"|\,|\./g, ''))/100;
                        }, 0 );
     
                    return $('<tr/>')
                        .append( '<td colspan="1">'+group+'</td>' )
                        .append( '<td>'+total+'</td>' )
                },
                dataSrc: ['main_character']
            },
*/            
        "drawCallback": function () {
                var api = this.api(), data;
            // Total over all pages
                total = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return parseInt(a) + parseFloat($(b).text().replace(/"|\,|\./g, ''))/100;
                    }, 0 );
            // Update footer
                $('#paps_total').html(total);
                $("img").unveil(100);
                ids_to_names();
                $('[data-toggle="tooltip"]').tooltip();
              }
    } );
      $('#livepve').DataTable();

    $('#paps').on('click', '#paps_count', function () {
        id = $(this).data('id');
        $('#operations').hide();
        $('#pap_box').show();
        
          paps = $('#paps_character').DataTable( {
              destroy: true,
              searching:false,
              responsive: true,
              "pageLength": 10,
              "ajax": {
                url: "/stats/paps/",
                data: function(d){
                    d.period = $('#period').find(":selected").val();
                    d.main = $("#main").is(':checked')?1:0;
                    d.character_id = id;
                    d.tag = $('#tags').val();
                }
              },
              "columns": [
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
                                return a + parseFloat($(b).text().replace(/"|\,|\./g, ''))/100;
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
    });

    $('#paps_character').on('click', '#ship_count', function () {
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
              searching:false,
              responsive: true,
              "pageLength": 25,
              "ajax": {
                url: "/stats/paps/operations/",
                data: function(d){
                    d.character_id = u_id;
                    d.period = $('#period').find(":selected").val();
                    d.ship_type_id = s_id;
                    d.tag = $('#tags').val();
                }
              },
              "columns": [
    //            {data: 'corporation', name: 'corporation'},
                {data: 'title', name: 'title'},
                {data: 'tag', name: 'tag'},
                {data: 'start_at', name: 'start_at'},
                {data: 'fc', name: 'fc'},
                {data: 'staging_sys', name: 'staging_sys'},
              ],
              order: [[3, 'desc']],
              "drawCallback": function () {
                    total = this.api().data().count();
                    //$('#operation_count').html(total+' times');
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
