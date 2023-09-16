<?php
    // TRAER LOS DATOS DE LA DATABASE
    global $wpdb;
    $query = "SELECT * FROM {$wpdb->prefix}usuarios";
    $lista_usuarios = $wpdb->get_results($query, ARRAY_A);

    // SI LA TABLA ESTÁ VACÍA, MUESTRA DATOS VACÍOS
    if(empty($lista_usuarios)){
        $lista_usuarios = array();
    }
?>
<div class="wrap">
    <?php
        echo "<h1 class='wp-heading-inline'>" . get_admin_page_title() . "</h1>";
        echo "<p>En este panel se tiene acceso a una tabla con los usuarios cargados en la base de datos, los usuarios se pueden filtrar por ID, expandir, editar o borrar, tambien hay un botón donde podes agregar un usuario nuevo.</p>";
    ?>
    <!-- CUANDO SE SELECCIONA EL BOTON, REDIRIGIR A "administrar.php" -->
    <button id="btnRedireccionar" type="button" class="btn btn-primary">Añadir nuevo usuario</button>
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#buscarUsuarioModal">Buscar Usuario</button>
    <br><br><br>

    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <th >ID</th>
            <th >Nombre y apellido</th>
            <th >Acciones</th>
        </thead>

        <tbody id="the-list">
            <?php
                // RECORRE LA TABLA USUARIOS Y MUESTRA LOS DATOS
                foreach ($lista_usuarios as $key => $value) {
                    $userid = $value['userid'];
                    $nombre_apellido = $value['nombre_apellido'];

                    echo "
                        <tr>
                            <th >$userid</th>
                            <th >$nombre_apellido</th>
                            <td>
                                <button data-ver='$userid' type='button' data-toggle='modal' data-target='#ModalCenter' class='btn btn-secondary'>Expandir</button>
                                <button data-edit='$userid' type='button' class='btn btn-primary'>Editar</button>
                                <button type='button' data-toggle='modal' data-target='#ModalBorrar' class='btn btn-danger btn-borrar'>Borrar</button>
                            </td>
                        </tr>
                    ";
                }
            ?>
        </tbody>
    </table>
</div>

<!-- MODAL CONFIRMAR BORRAR-->
<div class="modal fade" id="ModalBorrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Borrar usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>


        <div class="modal-body">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span>¿Estás seguro que quieres borrar este usuario?</span>
                </div>
            </div>
        </div>
      <div class="modal-footer">
        <?php
            echo "
                <button type='button' data-dismiss='modal' class='btn btn-secondary' data-dismiss='modal'>Cancelar</button>
                <button data-id='$userid' type='button' class='btn btn-danger btn-borrar'>Borrar</button>
            ";
        ?>
      </div>
    </div>
  </div>
</div>

<!-- MODAL BUSCAR -->
<div class="modal fade" id="buscarUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Buscar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Input para ingresar el userid -->
        <div class="form-group">
          <label for="useridInput">Ingrese el ID del usuario</label>
          <input type="text" class="form-control" id="userIdInput">
        </div>
      </div>
      <div class="modal-footer">
        <!-- Botón para buscar -->
        <button type="button" class="btn btn-primary" id="btnBuscarUsuario">Buscar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EXPANDIR -->
<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>


        <div class="modal-body">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">ID</span>
                </div>
                <span class="form-control" id="modalUserID" readonly></span>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">@</span>
                </div>
                <span class="form-control" id="modalUsername" readonly></span>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">Nombre y apellido</span>
                </div>
                <span class="form-control" id="modalName" readonly></span>
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">DNI</span>
                </div>
                <span class="form-control" id="modalDni" readonly></span>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">Teléfono</span>
                </div>
                <span class="form-control" id="modalTelefono" readonly></span>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2" readonly>Mail</span>
                </div>
                <span class="form-control" id="modalMail" readonly></span>
            </div>
        </div>
      <div class="modal-footer">
        <?php
            echo "
                <button type='button' data-dismiss='modal' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>
                <button data-edit='$userid' type='button' data-dismiss='modal' class='btn btn-primary'>Editar</button>
                <button type='button' data-dismiss='modal' data-toggle='modal' data-target='#ModalBorrar' class='btn btn-danger btn-borrar'>Borrar</button>

            ";
        ?>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="ModalCenterTwo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>

        <div class="modal-body">
            <input type="hidden" id="modalUserID" name="modalUserID" value="">
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">ID</span>
                </div>
                <input type="text" class="form-control" id="modalUserIDEdit" name="modalUserIDEdit" readonly>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">@</span>
                </div>
                <input type="text" class="form-control" id="modalUsernameEdit" name="modalUsernameEdit">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">Nombre y apellido</span>
                </div>
                <input type="text" class="form-control" id="modalNameEdit" name="modalNameEdit">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">DNI</span>
                </div>
                <input type="text" class="form-control" id="modalDniEdit" name="modalDniEdit">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">Teléfono</span>
                </div>
                <input type="text" class="form-control" id="modalTelefonoEdit" name="modalTelefonoEdit">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">Mail</span>
                </div>
                <input type="text" class="form-control" id="modalMailEdit" name="modalMailEdit">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="guardarCambiosBtn">Editar</button>

        </div>
    </div>
  </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('btnRedireccionar').addEventListener('click', function () {
            window.location.href = '<?php echo admin_url('admin.php?page=sp_menu_usuarios'); ?>';
        });
    });
</script>