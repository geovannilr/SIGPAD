@extends('template')
@section('content')

 
<ol class="breadcrumb"  style="text-align: center; margin-top: 1em;z-index: 0" >
        <li class="breadcrumb-item">
          <h5> <a href="{{ route('categoriaTDG.index') }}" style="margin-left: 0em"><i class="fa fa-arrow-left fa-lg" style="z-index: 1;margin-top: 0em;margin-right: 0.5em; color: black"></i></a>      Categoría TDG </h5>
        </li>
        <li class="breadcrumb-item active">Nueva categoría de Trabajo de Graduación </li>
</ol>

   <div class="row">
              <div class="col-md-6"></div>
              <div class="col-md-6 "> </div>
    </div>
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
    		{!! Form:: open(['route'=>'categoriaTDG.store','method'=>'POST']) !!}
    			@include('categoriaTDG.forms.formCreate')
        <div class="row">
          <div class="form-group col-sm-6">
            {!! Form::submit('Registrar',['class'=>'btn btn-primary']) !!}
          </div>
        </div>
				</div> 
			  {!! Form:: close() !!}
  </div>
  <script type="text/javascript">
  // run pre selected options
  $('#tiposSkill').multiSelect({
     selectableOptgroup: true,
    selectableHeader: "<input type='text'  class='search-input form-control' autocomplete='on' placeholder='Buscar'>",
    selectionHeader: "<input type='text'  class='search-input form-control' autocomplete='on'  placeholder='Buscar'>",
    afterInit: function(ms){

        var that = this,

        $selectableSearch = that.$selectableUl.prev(),
        $selectionSearch = that.$selectionUl.prev(),
        selectableSearchString = '#'+that.$container.attr('id')+'  .ms-elem-selectable:not(.ms-selected)',
        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString,{
                   'show': function () {

                        $(this).prev(".ms-optgroup-label").show();
                        $(this).show();
                    },
                    'hide': function () {
                        $(this).prev(".ms-optgroup-label").hide();
                        $(this).hide();
                    }
        })
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
    },afterSelect: function(){
      this.qs1.cache();
      this.qs2.cache();
    },
    afterDeselect: function(){
      this.qs1.cache();
      this.qs2.cache();

    }
  });
  

</script>
@stop
