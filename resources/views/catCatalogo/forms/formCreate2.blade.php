<div class="row">
    <div class="form-group col-sm-4">
        {!! Form::label('Nombre ') !!}
        {!!Form::text('nombre_gen_cat',null,['class'=>'form-control ','placeholder'=>'Ingrese el nombre de catálogo'])  !!}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('Descripción') !!}
        {!!Form::text('descripcion_gen_cat',null,['class'=>'form-control ','placeholder'=>'Nombre de controlador. Ej:cat_idi_idiomaController'])  !!}
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('Tipo:') !!}
        {!!Form::text('tipo_gen_cat',null,['class'=>'form-control ','placeholder'=>'Por defecto: administrador'])  !!}
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('Ruta') !!}
        {!!Form::text('ruta_gen_cat',null,['class'=>'form-control ','placeholder'=>'Ingrese el slug o ruta. Ej:catEstado'])  !!}
    </div>
</div>