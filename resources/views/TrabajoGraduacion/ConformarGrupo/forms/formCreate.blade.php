				<div class="row">
					<div class="form-group col-sm-4">
					
						{!! Form::label('Nombre') !!}
						{!!Form::text('name',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre Completo'])  !!}

					</div>
					<div class="form-group col-sm-4">
					
						{!! Form::label('Usuario') !!}
						{!!Form::text('user',null,['class'=>'form-control ','placeholder'=>'Ingrese nombre usuario'])  !!}

					</div>
					<div class="form-group col-sm-4">
					
					{!! Form::label('Rol') !!}
					{!! Form::select('rol', $roles,null,['class'=>'form-control']) !!}

				</div>	
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						
						{!! Form::label('Contraseña') !!}
						{!!Form::password('password',['class'=>'form-control ','placeholder'=>'Ingrese una contraseña'])  !!}
					</div>
					<div class="form-group col-sm-4">
						
						{!! Form::label('Confirmar Contraseña') !!}
						{!!Form::password('password_confirmation',['class'=>'form-control ','placeholder'=>'Confirme contraseña'])  !!}
					</div>
					<div class="form-group col-sm-4">
						
						{!! Form::label('Correo') !!}
						{!!Form::text('email',null,['class'=>'form-control ','placeholder'=>'Ingrese un correo'])  !!}

					</div>
				</div>
				

				