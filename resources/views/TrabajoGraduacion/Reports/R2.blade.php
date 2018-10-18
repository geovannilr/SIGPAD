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
                dom: '<"top"l>frt<"bottom"Bip><"clear">',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Grupos de trabajo de graduación y Tribunal Evaluador'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Grupos de trabajo de graduación y Tribunal Evaluador'
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Grupos de trabajo de graduación y Tribunal Evaluador'
                    },
                    {
                        extend: 'print',
                        title: 'Grupos de trabajo de graduación y Tribunal Evaluador'
                    }
                ],
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                info : false,
                bLengthChange: false,
                searching: false,
                paging: false,
            });
        });

    </script>
    <ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item ">
            <h5>
                <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em">
                    <i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i>
                </a>Reporte
            </h5>
        </li>
        <li class="breadcrumb-item active">{{$title}}</li>
    </ol>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
        <br>
        <br>
    </div>
    <br/>
    <div class="table-responsive">
    <table class="table table-hover table-striped  display" id="listTable">
        <thead>
            <th>Docente</th>
            <th>Carnet</th>
            <!---<th>Líder</th>-->
            <th>Asignaciones</th>
        </thead>
        <tbody>
        @foreach($datos as $dato)
        <tr>
            <td style="width: 20%;">
                {{$dato->Docente}}
            </td>
            <td>
                {{$dato->CarnetDoc}}
            </td>
            <!-- <td>
                $dato->Lider
            </td>-->
            <td style="text-align: center; width: 60%;">
                <div>
                    <table class="table table-bordered">
                        <th>Grupo</th>
                        <th>Rol</th>
                        @foreach($grupos as $grupo)
                            @if($grupo->CarnetDoc == $dato->CarnetDoc)
                                <tr>
                                    <td>{{$grupo->NumGrupo}}</td>
                                    <td>{{$grupo->TribunalRol}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div><!-- Modal Detalle de Docente Disponibles -->
<div class="modal fade" id="modalAgregarTribunal" tabindex="-1" role="dialog" aria-labelledby="Detalle docentes disponibles" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 60%">
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