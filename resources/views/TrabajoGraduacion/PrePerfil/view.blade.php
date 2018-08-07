@extends('template')
{!!Html::script('js/TrabajoGraduacion/prePerfil.js')!!}
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
            <h5>Detalle del Tribunal Evaluador</h5>
        </li>
        <li class="breadcrumb-item active">Edición</li>
    </ol>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
        @can('grupo.index')
            <div class="col-sm-3" id="divBtnAddTrib">
                <button class="btn btn-primary" onclick="prepareModal(vg_id_pdg_gru)" id="btnAddTrib"><i class="fa fa-user-plus"></i></button>
            </div>
        @endcan
        <br>
        <br>
    </div>
    <br/>
    <div class="table-responsive">
    <table class="table table-hover table-striped  display" id="listTable">
        <thead>
            <th>Nombre</th>
            <th>Rol</th>
            <th>Contacto</th>
            <th>Acciones</th>
        </thead>
        <tbody>

        @foreach ($tribunal as $trib)
        <tr>
            <td>
                {{$trib->name}}
            </td>
            <td>
                {{$trib->nombre_tri_rol}}
            </td>
            <td>
                {{$trib->email}}
            </td>
            <td>
                <div class="btn-group">
                    <button type="submit" id="btn-del-trib" class="btn btn-danger btn-eliminar" onclick="delRelTrib('{{$trib->id_pdg_tri_gru}}','{{$trib->nombre_tri_rol}}')"><i class="fa fa-trash"></i></button>
                </div>
            </td>
        </tr>
        @endforeach
        <script type="text/javascript">
            var vg_anio = null;
            var vg_id_pdg_gru = {{$id}};
        </script>
        </tbody>
    </table>
</div><!-- Modal Detalle de Docente Disponibles -->
<div class="modal fade" id="modalAgregarTribunal" tabindex="-1" role="dialog" aria-labelledby="Detalle docentes disponibles" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLongTitle">Agregar Docente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione un docente de los disponibles a continuación:
                <div id="modalDetalleBody" class="modal-body" style="overflow-y: scroll;">
                ... <!--EJRG: es necesario arreglar el MODAL para que no se extienda demasiado en pantalla style="height:50%; overflow-y: scroll;"-->
                </div>
            </div>
            <div class="modal-footer" id="footerModal">
                <div class="btn-group" id="divBtn">
                </div>
            </div>
        </div>
    </div>
</div>
@stop