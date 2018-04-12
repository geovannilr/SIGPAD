//DELETE  USUARIO
function borrarUsuario(id){
        swal({
          title: "Eliminar Vehículo",
          text: "Estás seguro que deseas eliminar este Vehículo?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Si, Borrar",
          cancelButtonText: "No, Cancelar",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm) {
          if (isConfirm) {
            var parametros = {
                "idPt" :id,
                };
        
        $.ajax({
                data:  parametros,
                url:   '<?php echo base_url();?>vehicle/destroy/'+id,
                type:  'post',
                beforeSend: function () {        
                },
                success:  function (response) {
                    swal("Hecho!", "El vehículo ha sido eliminado.", "success");
                    location.reload();
                }
            });
            
          } else {
            swal("Cancelado", "Eliminar vehículo cancelado.", "error");
          }
        });
    }