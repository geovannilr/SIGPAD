<ul class="nav nav-tabs" id="tabGrupos" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="activos-tab" data-toggle="tab" href="#activos" role="tab" aria-controls="activos" aria-selected="true">Activos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="finalizados-tab" data-toggle="tab" href="#finalizados" role="tab" aria-controls="finalizados" aria-selected="false">Finalizados</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="activos" role="tabpanel" aria-labelledby="activos-tab">
        <br><br>
        <div class="table-responsive">
            <table class="table table-hover table-striped  display" id="listTable">

                <thead>
                @if(!isset($numero))
                    <th>Grupo</th>
                @endif
                <th>Tema</th>
                <th>Fecha Creación</th>
                <th>Estado</th>
                <th>Tipo</th>
                @can('perfil.edit')
                    <th>Modificar</th>
                @endcan
                @can('perfil.destroy')
                    <th>Eliminar</th>
                @endcan
                <th>Documento</th>
                <th>Resumen</th>
                @can('perfil.aprobar')
                    <th>Aprobar</th>
                @endcan
                @can('perfil.rechazar')
                    <th>Rechazar</th>
                @endcan

                </thead>
                <tbody>

                @foreach($perfiles as $perfil)
                    @if($perfil->finalizo != 1)
                    <tr>
                        @if(!isset($numero))
                            <td>{{ $perfil->numero_pdg_gru }}</td>
                        @endif
                        <td>{{ $perfil->tema_pdg_per }}</td>
                        <td>{{ date_format(date_create($perfil->fecha_creacion_per), 'd/m/Y H:i:s')}}</td>
                        <td>
                            @if($perfil->id_cat_sta == "9" )
                                <span class="badge badge-success">{{ $perfil->nombre_cat_sta }}</span>&nbsp;
                            @else
                                @if($perfil->id_cat_sta == "11" )
                                    <span class="badge badge-danger">{{ $perfil->nombre_cat_sta }}</span>&nbsp;
                                @else
                                    <span class="badge badge-info">{{ $perfil->nombre_cat_sta }}</span>&nbsp;
                                @endif
                            @endif

                        </td>
                        <td>{{ $perfil->nombre_cat_tpo_tra_gra}}</td>
                        @can('prePerfil.edit')
                            <td>
                                <a class="btn btn-info" href="{{route('perfil.edit',$perfil->id_pdg_per)}}"><i class="fa fa-pencil"></i></a>

                            </td>
                        @endcan
                        @can('perfil.destroy')
                            <td style="text-align: center;">
                                {!! Form::open(['route'=>['perfil.destroy',$perfil->id_pdg_per],'method'=>'DELETE','class' => 'deleteButton']) !!}
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </div>
                                {!! Form:: close() !!}
                            </td>
                        @endcan

                        <td style="text-align: center;">
                            {!! Form::open(['route'=>['downloadPerfil'],'method'=>'POST']) !!}
                            <div class="btn-group">
                                {!!Form::hidden('archivo',$perfil->id_pdg_per,['class'=>'form-control'])!!}
                                {!!Form::hidden('grupo',$perfil->id_pdg_gru,['class'=>'form-control'])!!}
                                <button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
                            </div>
                            {!! Form:: close() !!}
                        </td>
                        <td>
                            {!! Form::open(['route'=>['downloadPerfilResumen'],'method'=>'POST']) !!}
                            <div class="btn-group">
                                {!!Form::hidden('archivo',$perfil->id_pdg_per,['class'=>'form-control'])!!}
                                {!!Form::hidden('grupo',$perfil->id_pdg_gru,['class'=>'form-control'])!!}
                                <button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
                            </div>
                            {!! Form:: close() !!}
                        </td>
                        @can('perfil.aprobar')
                            <td style="text-align: center;">
                                @if($perfil->id_cat_sta != "9"  &&  $perfil->id_cat_sta != "11" )
                                    {!! Form::open(['route'=>['aprobarPerfil'],'method'=>'POST','class'=>'aprobar']) !!}

                                    <div class="btn-group">
                                        {!!Form::hidden('idPerfil',$perfil->id_pdg_per,['class'=>'form-control'])!!}
                                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i></button>
                                    </div>
                                    {!! Form:: close() !!}
                                @endif
                            </td>
                        @endcan
                        @can('perfil.rechazar')

                            <td style="text-align: center;">
                                @if($perfil->id_cat_sta != "9"  &&  $perfil->id_cat_sta != "11" )
                                    {!! Form::open(['route'=>['rechazarPerfil'],'method'=>'POST','class'=>'rechazar']) !!}
                                    <div class="btn-group">
                                        {!!Form::hidden('idPerfil',$perfil->id_pdg_per,['class'=>'form-control'])!!}
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i></button>
                                    </div>
                                    {!! Form:: close() !!}
                                @endif
                            </td>
                        @endcan
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="finalizados" role="tabpanel" aria-labelledby="finalizados-tab">
        <br><br>
        <div class="table-responsive">
            <table class="table table-hover table-striped  display" id="listTableFin">
                <thead>
                @if(!isset($numero))
                    <th>Grupo</th>
                @endif
                <th>Tema</th>
                <th>Fecha Creación</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>Documento</th>
                <th>Resumen</th>

                </thead>
                <tbody>

                @foreach($perfiles as $perfil)
                    @if($perfil->finalizo == 1)
                    <tr>
                        @if(!isset($numero))
                            <td>{{ $perfil->numero_pdg_gru }}</td>
                        @endif
                        <td>{{ $perfil->tema_pdg_per }}</td>
                        <td>{{ date_format(date_create($perfil->fecha_creacion_per), 'd/m/Y H:i:s')}}</td>
                        <td>
                            @if($perfil->id_cat_sta == "9" )
                                <span class="badge badge-success">{{ $perfil->nombre_cat_sta }}</span>&nbsp;
                            @else
                                @if($perfil->id_cat_sta == "11" )
                                    <span class="badge badge-danger">{{ $perfil->nombre_cat_sta }}</span>&nbsp;
                                @else
                                    <span class="badge badge-info">{{ $perfil->nombre_cat_sta }}</span>&nbsp;
                                @endif
                            @endif

                        </td>
                        <td>{{ $perfil->nombre_cat_tpo_tra_gra}}</td>

                        <td style="text-align: center;">
                            {!! Form::open(['route'=>['downloadPerfil'],'method'=>'POST']) !!}
                            <div class="btn-group">
                                {!!Form::hidden('archivo',$perfil->id_pdg_per,['class'=>'form-control'])!!}
                                {!!Form::hidden('grupo',$perfil->id_pdg_gru,['class'=>'form-control'])!!}
                                <button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
                            </div>
                            {!! Form:: close() !!}
                        </td>
                        <td>
                            {!! Form::open(['route'=>['downloadPerfilResumen'],'method'=>'POST']) !!}
                            <div class="btn-group">
                                {!!Form::hidden('archivo',$perfil->id_pdg_per,['class'=>'form-control'])!!}
                                {!!Form::hidden('grupo',$perfil->id_pdg_gru,['class'=>'form-control'])!!}
                                <button type="submit" class="btn btn-dark"><i class="fa fa-download"></i></button>
                            </div>
                            {!! Form:: close() !!}
                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#listTableFin").DataTable({
            language: {
                url: 'es-ar.json' //Ubicacion del archivo con el json del idioma.
            },
            dom: '<"top"l>frt<"bottom"Bip><"clear">',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    },
                    title: 'Listado de Perfiles'
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    },
                    title: 'Listado de Perfiles'
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    },
                    title: 'Listado de Perfiles'
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3]
                    },
                    title: 'Listado de Perfiles'
                }


            ],
            order: [ 1, 'asc' ],
        });});
</script>