@extends('template')

@section('content')
	@if(Session::has('message'))
		<script type="text/javascript">
            $( document ).ready(function() {
				@if(Session::has('tipo') == 'error')
                swal("", "{{Session::get('message')}}", "error");
				@else
                swal("", "{{Session::get('message')}}", "success");
				@endif
            });
		</script>
	@endif
	<script type="text/javascript">
        $( document ).ready(function() {
            $("#listTable").DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                info : false,
                bLengthChange: false,
            });
        });
        function downloadFormato(id){
            $("#idFormato").val(id);
            var formul = $("#frmDownload");
            formul.submit();
            $("#frmDownload").submit();
        }
	</script>
	<ol class="breadcrumb" style="text-align: center; margin-top: 1em">
		<li class="breadcrumb-item ">
			<h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em">
					<i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i>
				</a>Trabajo de Graduación</h5>
		</li>
		<li class="breadcrumb-item active">Formatos</li>
	</ol>
	<div class="row">
		<div class="col-sm-3"></div>
		<div class="col-sm-3"></div>
		<div class="col-sm-3"></div>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-striped  display" id="listTable">
			<thead>
			<th>Nombre</th>
			<th>Descripción</th>
			<th>Descarga</th>
			</thead>
			<tbody>
			@foreach($formatos as $formato)
				<tr>
					<td>
						{{$formato->nombre}}
					</td>
					<td>
						{{$formato->descripcion}}
					</td>
					<td>
						<button class="btn btn-dark" onclick="downloadFormato('{{$formato->codigo}}');"><i class="fa fa-download"></i></button>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>

	<div id="divFrmDownload" style="display: none;">
		{!! Form::open(['route'=>['descargaFormato'],'method'=>'POST', 'id'=>'frmDownload']) !!}
		<input type="hidden" name="idFormato" id="idFormato" />
		{!! Form:: close() !!}
	</div>
@stop