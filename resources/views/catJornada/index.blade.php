@extends('template')

@section('content')
@if(Session::has('message'))
  		<script type="text/javascript">
  			$( document ).ready(function() {
    			swal("", "{{Session::get('message')}}", "success");
			});
  		</script>		
@endif
<script type="text/javascript">
    const iconOrder = "<i class='fa fa-sort-amount-asc'></i>";
    const iconCancel = "<i class='fa fa-times'></i>";
	$( document ).ready(function() {
	    global = true;
	    editOrder();
		 $('.deleteButton').on('submit',function(e){
        if(!confirm('Estas seguro que deseas eliminar jornada')){

              e.preventDefault();
        	}
      	});
		
    	$("#listTable").DataTable({
            language: {
                url: 'es-ar.json' //Ubicacion del archivo con el json del idioma.
            },
        dom: '<"top"l>frt<"bottom"Bip><"clear">',
        buttons: [
           {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de jornadas'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de jornadas'
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de jornadas'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0]
                },
                title: 'Listado de jornadas'
            }


        ],
         responsive: {
            details: {
                type: 'column'
            }
        },
            ordering: false,
    	});

        $(".btnUp,.btnDwn").click(function(){
            var row = $(this).parents("tr:first");
            if ($(this).is(".btnUp")) {
                row.insertBefore(row.prev());
            } else {
                row.insertAfter(row.next());
            }
        });
	});

	function editOrder(){
	    global = !(global);
        var mode = global ? 1 : 0;
	    toggleButtons(mode);
	    toggleColumnButtons(mode);
    }

    function toggleButtons(mode){
        if(mode==0){
            $("#btnNewOrder").show();
            $("#btnSaveOrder").hide();
            $("#btnEditOrder").html(iconOrder + " Ordenar");
        }else{
            $("#btnNewOrder").hide();
            $("#btnSaveOrder").show();
            $("#btnEditOrder").html(iconCancel + " Cancelar");
        }
    }

    function toggleColumnButtons(mode) {
        var crudBtns = $(".primaryBtns");
        var orderBtns = $(".secondaryBtns");
        $.each(crudBtns, function (indx) {
            if (mode == 0)
                $(this).show();
            else
                $(this).hide();
        });
        $.each(orderBtns, function (indx) {
            if (mode == 0)
                $(this).hide();
            else
                $(this).show();
        });
    }

	function saveOrder() {
	    var url = "{{route('sortCatJornada')}}";
	    var data = getSortedRowsData();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN':  getCurrentTkn()
            }
        });
        $.ajax({
            type: 'POST',
            url : url,
            data : {data: data},
            success: function (data) {
                swal({
                    title:data.errorCode == 0 ? "¡Éxito!" : "¡Aviso!",
                    text: data.errorMessage,
                    icon: data.errorCode == 0 ? "success" : "warning",
                    button: "Aceptar",
                }).then((respuesta)=>{
                    location.reload();
                });
            },
            error: function (xhr, status) {
                swal("", "Su solicitud no pudo ser procesada!", "error");
            }
        });
    }

    function getSortedRowsData() {
        var rows = $('#listTable > tbody > tr');
        var elements = [];
        rows.each(function (i) {
            var inputs = $(this).find("td > input");
            var element = {id: inputs[0].value, ordini: inputs[1].value, ordfin: (i + 1)};
            elements.push(element);
        });
        return elements;
    }
</script>
		<ol class="breadcrumb"  style="text-align: center; margin-top: 1em">
	        <li class="breadcrumb-item">
	          <h5><a href="{{ route('catCatalogo.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>     Jornadas</h5>
	        </li>
	        <li class="breadcrumb-item active">Listado Jornadas</li>
		</ol>
<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-3"></div>
    <div class="col-sm-3"></div>
    @can('catJornada.create')
        <div class="col-sm-3">
            <a class="btn btn-primary" id="btnNewOrder" href="{{route('catJornada.create')}}" ><i class="fa fa-plus"></i> Nueva Jornada </a>
            <button type="button" class="btn btn-primary" id="btnSaveOrder" onclick="saveOrder();"><i class="fa fa-save"></i> Guardar</button>
            <button type="button" class="btn btn-secondary" id="btnEditOrder" onclick="editOrder();"><i class="fa fa-sort-amount-asc"></i> Ordenar</button>
        </div>
    @endcan
</div>

		<br>
  		<div class="table-responsive">
  			<table class="table table-hover table-striped  display" id="listTable">

  				<thead>
					<th>Jornada</th>
                     @can('catJornada.edit')
                    <th style="text-align: center;">Acciones</th>
                    @endcan
                    @can('catJornada.destroy')
                    @endcan
  				</thead>
  				<tbody>
  				@foreach($catJornada as $catJornad)
					<tr>
						<td>
                            {{ $catJornad->descripcion_cat_tpo_jrn_dcn}}
                            <input type="hidden" value="{{$catJornad->id_cat_tpo_jrn_dcn}}" name="id"/>
                            <input type="hidden" value="{{$catJornad->orden_cat_tpo_jrn_dcn}}" name="ordini"/>
                        </td>
                        <td style="width: 160px">
                            <div class="row primaryBtns" >
                                @can('catJornada.edit')
                                <div class="col-6">
                                    <a class="btn " style="background-color:  #102359;color: white" href="{{route('catJornada.edit',$catJornad->id_cat_tpo_jrn_dcn)}}"><i class="fa fa-pencil"></i></a>
                                </div>
                                @endcan
                                @can('catJornada.destroy')
                                    <div class="col-6">
                                        {!! Form::open(['route'=>['catJornada.destroy',$catJornad->id_cat_tpo_jrn_dcn],'method'=>'DELETE','class' => 'deleteButton']) !!}
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </div>
                                        {!! Form:: close() !!}
                                    </div>
                                @endcan
                            </div>
                            <div class="row secondaryBtns" >
                                <div class="col-6">
                                    <button type="button" class="btn btn-success btnUp"><i class="fa fa-arrow-up"></i></button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-danger btnDwn"><i class="fa fa-arrow-down"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>
				@endforeach 
				</tbody>
			</table>
	   </div>
@stop