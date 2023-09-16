jQuery(document).ready(function($){

    // BORRAR USUARIO
    $(document).on('click','button[data-id]',function(){
            var id = this.dataset.id;
            var url = SolicitudesAjax.url;
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    action : "peticioneliminar",
                    nonce : SolicitudesAjax.seguridad,
                    id: id,
                },
                success:function(){
                    location.reload();
                }
            });
    });

    // EXPANDIR USUARIO
    $(document).on('click', 'button[data-ver]', function () {
        var userId = jQuery(this).data('ver');
        var url = SolicitudesAjax.url;
        var nonce = SolicitudesAjax.seguridad;

        jQuery.ajax({
            type: "POST",
            url: url,
            data: {
                action: "expandirUsuario",
                nonce: nonce,
                id: userId
            },
            success: function (response) {
                if (response.success) {
                    var usuario = response.data;

                    $('#modalUserID').text(usuario.userid);
                    $('#modalUsername').text(usuario.username);
                    $('#modalName').text(usuario.nombre_apellido);
                    $('#modalDni').text(usuario.dni);
                    $('#modalTelefono').text(usuario.telefono);
                    $('#modalMail').text(usuario.mail);

                    $('#ModalCenter').modal('show');
                } else {
                    alert('Error al obtener los datos del usuario: ' + response.data);
                }
            },
            error: function (textStatus) {
                alert('Error en la solicitud AJAX: ' + textStatus);
            }
        });
    });
    
    // Cuando se haga clic en el botón "Buscar"
    $("#btnBuscarUsuario").click(function() {
        var userId = $("#userIdInput").val(); // Obtener el ID ingresado
        var url = SolicitudesAjax.url;
        var nonce = SolicitudesAjax.seguridad;
    
        $.ajax({
        type: "POST",
        url: url,
        data: {
            action: "expandirUsuario",
            nonce: nonce,
            id: userId
        },
        success: function(response) {
            if (response.success) {
            var usuario = response.data;
    
            // Rellenar el modal con los datos del usuario
            $('#modalUserID').text(usuario.userid);
            $('#modalUsername').text(usuario.username);
            $('#modalName').text(usuario.nombre_apellido);
            $('#modalDni').text(usuario.dni);
            $('#modalTelefono').text(usuario.telefono);
            $('#modalMail').text(usuario.mail);
    
            // Cerrar el modal de búsqueda
            $('#buscarUsuarioModal').modal('hide');
    
            // Mostrar el modal con los datos del usuario
            $('#ModalCenter').modal('show');
            } else {
            alert('Error al obtener los datos del usuario: ' + response.data);
            }
        },
        error: function(textStatus) {
            alert('Error en la solicitud AJAX: ' + textStatus);
        }
        });
    });
  
  // Limpiar el campo de entrada cuando se cierre el modal de búsqueda
  $('#modalBuscarUsuario').on('hidden.bs.modal', function () {
    $('#userIdInput').val('');
  });

    // EDITAR USUARIO
    $(document).on('click', 'button[data-edit]', function () {
        var id = $(this).data('edit');
        var url = SolicitudesAjax.url;
        
        $.ajax({
            type: "POST",
            url: url,
            data: {
                action: "editarUsuario",
                nonce: SolicitudesAjax.seguridad,
                id: id,
            },
            success: function(response){
                if (response.success) {
                    // RELLENAR EL MODAL CON LOS DATOS
                    var usuario = response.data;
                    $('#modalUserIDEdit').val(usuario.userid);
                    $('#modalUsernameEdit').val(usuario.username);
                    $('#modalNameEdit').val(usuario.nombre_apellido);
                    $('#modalDniEdit').val(usuario.dni);
                    $('#modalTelefonoEdit').val(usuario.telefono);
                    $('#modalMailEdit').val(usuario.mail);
                    
                    $('#ModalCenterTwo').modal('show');
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function(response) {
                alert('Error en la solicitud AJAX.');
            }
        });
    });

    $('#guardarCambiosBtn').click(function() {
        var modalUserID = $('#modalUserIDEdit').val();
        var modalUsername = $('#modalUsernameEdit').val();
        var modalName = $('#modalNameEdit').val();
        var modalDni = $('#modalDniEdit').val();
        var modalTelefono = $('#modalTelefonoEdit').val();
        var modalMail = $('#modalMailEdit').val();

        var url = SolicitudesAjax.url;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                action: "guardarCambiosUsuario",
                nonce: SolicitudesAjax.seguridad,
                userid: modalUserID,
                username: modalUsername,
                nombre_apellido: modalName,
                dni: modalDni,
                telefono: modalTelefono,
                mail: modalMail
            },
            success: function(response) {
                if (response.success) {
                    $('#ModalCenterTwo').modal('hide');
                    location.reload();
                } else {
                    alert('Error al guardar los cambios: ' + response.data);
                }
            },
            error: function(response) {
                alert('Error en la solicitud AJAX.');
            }
        });
    });
});