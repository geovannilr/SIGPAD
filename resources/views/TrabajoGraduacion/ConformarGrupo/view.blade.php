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

    <ol class="breadcrumb">
        <li class="breadcrumb-item ">
            <h5>Detalle del Grupo</h5>
        </li>
        <li class="breadcrumb-item active">Edición</li>
    </ol>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
        @can('grupo.index')
            <div class="col-sm-3" id="divNuevo_viewConformarGrupo">Nuevo
                <button class="btn btn-primary" onclick="getDisponibles(vg_url,vg_anio);" id="btnNuevo_viewConformarGrupo"><i class="fa fa-plus"></i></button>
            </div>
        @endcan
        <br>
        <br>
    </div>
    <div class="table-responsive">
    <table class="table table-hover table-striped  display" id="listTable">
        <thead>
            <th>Carnet</th>
            <th>Nombre</th>
            <th>Cargo</th>
            <th>Acciones</th>
        </thead>
        <tbody>

        @foreach ($relaciones as $relacion)
        <tr>
            <td>{{$relacion->estudiante->carnet_gen_est}}</td>
            <td>{{$relacion->estudiante->nombre_gen_est}}</td>
            <td>
                @if($relacion->eslider_pdg_gru_est == 1)
                    Lider
                @else
                    Miembro
                @endif
            </td>
            <td>
                {!! Form::open(['route'=>['delRelacion',$relacion->id_pdg_gru_est],'method'=>'DELETE','class' => 'deleteButton']) !!}
                <div class="btn-group">
                    <button type="submit" class="btn btn-danger btn-eliminar"><i class="fa fa-trash"></i></button>
                </div>
                {!! Form:: close() !!}
            </td>
        </tr>
        @endforeach
        <script type="text/javascript">
            var vg_anio = null;
            var vg_id_pdg_gru = null;
            var vg_url = '{{\Illuminate\Support\Facades\URL::to('/')}}';
            $('.btn-eliminar').click(function(e){
                e.preventDefault() // No postear hasta confirmación
                if (confirm('¿Está seguro?')) {
                    $(e.target).closest('form').submit() // Postear el form
                }
            });
            @foreach ($relaciones as $relacion)
                vg_anio = {{$relacion->grupo->anio_pdg_gru}};
                vg_id_pdg_gru = {{$relacion->id_pdg_gru}};
            @endforeach
        </script>
        </tbody>
    </table>
</div><!-- Modal Detalle de Alumnos Sin Grupo -->
<div class="modal fade" id="modalAgregarAlumno" tabindex="-1" role="dialog" aria-labelledby="Detalle grupo de trabajo de graduación" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLongTitle">Agregar Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione un estudiante de los disponibles a continuación:
                <div id="modalDetalleBody" class="modal-body" style="height:50%; overflow-y: scroll;">
                ... <!--EJRG: es necesario arreglar el MODAL para que no se extienda demasiado en pantalla-->
                </div>
                <!---O busque alguno en específico:-->
                <!--Form::text('buscarEstudiante',null,['class'=>'form-control ','placeholder'=>'Filtrar búsqueda...','id'=>'inputBuscar'])-->
            </div>
            <div class="modal-footer" id="footerModal">
                {!! Form::open(['route'=>['aprobarGrupo'],'method'=>'POST']) !!}
                <div class="btn-group" id="divBoton">

                </div>
                {!! Form:: close() !!}
            <!-- EJRG begin -->
                {!! Form::open(['route'=>['verGrupo'],'method'=>'POST']) !!}
                <div class="btn-group" id="divBtnEditarGrupo">

                </div>
            {!! Form:: close() !!}
            <!-- EJRG end-->
            </div>
        </div>
    </div>
</div>
@stop