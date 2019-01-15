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
            $( ".btnEnviar" ).click(function() {
                if ($(this)[0].id == 'gen') {
                    $("#tipo").val(1);
                }else if ($(this)[0].id  == 'des') {
                    $("#tipo").val(2);
                }
                $("#grupo").val($(this)[0].name);
                $("#formReporte").submit();
            });
            $("#listTable").DataTable({
                language: {
                    url: 'es-ar.json'
                },
                dom: '<"top"l>frt<"bottom"Bip><"clear">',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        },
                        title: 'Listado de Grupos de trabajo de graduación'
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        },
                        title: 'Listado de Grupos de trabajo de graduación'
                    },
                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        },
                        title: 'Listado de Grupos de trabajo de graduación'
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        },
                        title: 'Listado de Grupos de trabajo de graduación'
                    }


                ],
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                order: [ 2, 'asc' ],
            });
        });


    </script>
    <ol class="breadcrumb" style="text-align: center; margin-top: 1em">
        <li class="breadcrumb-item ">
            <h5> <a href="{{ route('reportesTDG') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>{{$title}}</h5>
        </li>
        <li class="breadcrumb-item active">Listado</li>
    </ol>

    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
        <div class="col-sm-3"></div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-hover table-striped display" id="listTable">

            <thead>
            <th>Grupo</th>
            <th>Líder</th>
            <th>Estado</th>
            <th>Acciones</th>
            </thead>
            <tbody>

            @foreach($grupos as $grupo)
                @if($grupo->idEstado != 7 && $grupo->idEstado != 2 && $grupo->idEstado != 1)
                    <tr>
                        @if(empty($grupo->numeroGrupo))
                            <td>PENDIENTE</td>
                        @else
                            <td>{{ $grupo->numeroGrupo }}</td>
                        @endif

                        <td>{{ $grupo->Lider }}</td>
                        <td>
                            @if($grupo->finalizo == 1)
                                <p class="success">FINALIZADO</p>
                            @else
                                <p class="success">EN PROCESO</p>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a class="btn btn-dark" href="#" onclick="getGrupo({{ $grupo->ID }});"><i class="fa fa-eye"></i></a>
                            <button class="btn btn-success btnEnviar" id="gen" name="{{ $grupo->ID }}"><i class="fa fa-external-link-square"></i></button>
                            <button class="btn btn-primary btnEnviar" id="des" name="{{ $grupo->ID }}"><i class="fa fa-download"></i></button>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>



    <!-- Modal Detalle de grupo -->
    <div class="modal fade" id="detalleGrupo" tabindex="-1" role="dialog" aria-labelledby="Detalle grupo de trabajo de graduación" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Detalle de grupo de trabajo de graduación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalDetalleBody" class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>
    <div style="hidden: true;">
    {!! Form:: open(['route'=>'reportes/consolidadoNotas','method'=>'POST','id'=>'formReporte','target'=>'_blank']) !!}
        <input type="hidden" name="tipo" id="tipo">
        <input type="hidden" name="grupo" id="grupo">
    {!! Form:: close() !!}
    </div>
@stop