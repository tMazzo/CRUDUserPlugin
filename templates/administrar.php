<?php
global $wpdb;
$tabla = "{$wpdb->prefix}usuarios";

if(isset($_POST['btnguardar'])){
    $Username = sanitize_text_field($_POST['username']);
    $NombreApellido = sanitize_text_field($_POST['nombre_apellido']);
    $Dni = sanitize_text_field($_POST['dni']);
    $Telefono = sanitize_text_field($_POST['telefono']);
    $Mail = sanitize_email($_POST['mail']);

    $datos = [
        'userid' => null,
        'username' => $Username,
        'nombre_apellido' => $NombreApellido,
        'dni' => $Dni,
        'telefono' => $Telefono,
        'mail' => $Mail
    ];

    $wpdb->insert($tabla, $datos);
}
?>

<div class="wrap">
    <?php
    echo "<h1 class='wp-heading-inline'>" . get_admin_page_title() . "</h1>";
    echo "<p>En este panel se tiene acceso a la creación de un usuario, donde se ingresan los datos, si el dato se deja vacio se manda un dato NULL.</p>";
    ?>
    <button id="btnRedireccionar" type="button" class="btn btn-primary btnRedireccionar">Ver lista de usuarios</button>
    
    <br><br><br>

    <form method="post" action="">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input name="username" id="username" type="text" class="form-control" placeholder="Example_01" aria-label="ThomasJhon15" aria-describedby="basic-addon1">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Nombre y apellido</span>
            </div>
            <input name="nombre_apellido" id="nombre_apellido" type="text" class="form-control" placeholder="Example E. Example" aria-label="Thomas Jhonson" aria-describedby="basic-addon1">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">DNI</span>
            </div>
            <input name="dni" id="dni" type="text" class="form-control" placeholder="11223344" aria-label="11223344" aria-describedby="basic-addon1">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Teléfono</span>
            </div>
            <input name="telefono" id="telefono" type="text" class="form-control" placeholder="+54 9 11 12345678" aria-label="+54 9 11 12345678" aria-describedby="basic-addon1">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Mail</span>
            </div>
            <input name="mail" id="mail" type="text" class="form-control" placeholder="example@hotmail.com" aria-label="example@hotmail.com" aria-describedby="basic-addon1">
        </div>

        <button type="submit" name="btnguardar" id="btnguardar" class="btn btn-success btnRedireccionar">Agregar usuario</button>
        <button type="button" name="btnReiniciar" id="btnReiniciar" class="btn btn-secondary">Reiniciar datos</button>
    </form>
</div>

<!-- SCRIPT PARA REINICIAR DATOS CUANDO SE HACE CLIC EN EL BOTÓN -->
<script>
    // VOLVER A LA LISTA
    document.addEventListener('DOMContentLoaded', function () {
        // Selecciona todos los elementos con la clase 'btnRedireccionar'
        var botonesRedireccionar = document.querySelectorAll('.btnRedireccionar');
        
        // Agrega un evento clic a cada botón
        botonesRedireccionar.forEach(function(boton) {
            boton.addEventListener('click', function () {
                // Redirige a la página personalizada utilizando la ruta personalizada que definiste
                window.location.href = '<?php echo admin_url('admin.php?page=sp_menu'); ?>';
            });
        });
    });

    // REINICIAR DATOS
    document.addEventListener('DOMContentLoaded', function () {
        // DATOS
        var usernameInput = document.getElementById('username');
        var nombreApellidoInput = document.getElementById('nombre_apellido');
        var dniInput = document.getElementById('dni');
        var telefonoInput = document.getElementById('telefono');
        var mailInput = document.getElementById('mail');
            
        // BUTTON
        var reiniciarBoton = document.getElementById('btnReiniciar');

        // GUARDAR VALORES ORIGINALES
        var valorOriginalUsername = usernameInput.value;
        var valorOriginalNombreApellido = nombreApellidoInput.value;
        var valorOriginalDNI = dniInput.value;
        var valorOriginalTelefono = telefonoInput.value;
        var valorOriginalMail = mailInput.value;

        // RESTABLECER VALORES CUANDO SE HACE CLIC
        reiniciarBoton.addEventListener('click', function () {
            usernameInput.value = valorOriginalUsername;
            nombreApellidoInput.value = valorOriginalNombreApellido;
            dniInput.value = valorOriginalDNI;
            telefonoInput.value = valorOriginalTelefono;
            mailInput.value = valorOriginalMail;
        });
    });
</script>
