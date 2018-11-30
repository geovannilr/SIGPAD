@extends('template')
@section('content')

 
<ol class="breadcrumb"  style="text-align: center; margin-top: 1em;z-index: 0" >
        <li class="breadcrumb-item">
          <h5> <a href="{{ redirect()->getUrlGenerator()->previous() }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>      Experiencia Laboral </h5>
        </li>
        <li class="breadcrumb-item active">Nuevo Registro  </li> 
</ol>

 <!-- <div class="form-group col-sm-6 " >   </div> -->
      <div class="panel-body">
        @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
        @endif
        {!! Form:: open(['route'=>'laboral.store','method'=>'POST']) !!}
          @include('PerfilDocente.Catalogos.Laboral.forms.formCreate')
        <div class="row">
          <div class="form-group col-sm-6">
            {!! Form::submit('Registrar',['class'=>'btn btn-primary']) !!}
          </div>
        </div>
        </div> 
        {!! Form:: close() !!}
  </div>
<script type="text/javascript">
  
  $( document ).ready(function() {
 $("#from").datepicker({
        format: 'yyyy',
        autoclose: 1,
        locale:'es',
        viewMode: "years", 
        minViewMode: "years",
        todayHighlight: false,
        //endDate: new Date()
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#to').datepicker('setStartDate', minDate);
        $("#to").val($("#from").val());
        $(this).datepicker('hide');
    });

    $("#to").datepicker({
        format: 'yyyy',
        todayHighlight: true,
        locale:'es',viewMode: "years", 
        minViewMode: "years"
    }).on('changeDate', function (selected) {
        $(this).datepicker('hide');
    });
  });
  
</script>
@stop
