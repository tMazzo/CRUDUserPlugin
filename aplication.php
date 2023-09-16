<?php
/*
Plugin Name: API Users  
Plugin URI: https://portfolioweb-tmazzo.web.app 
Description: Este es un plugin de ejemplo para WordPress, donde podrás mostrar, crear, editar y borrar usuarios a tu antojo.
Version: 1.2
Author: Tomás A. Mazzo
*/

function activar() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'usuarios';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        userid INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        nombre_apellido VARCHAR(255) NOT NULL,
        dni VARCHAR(10) NOT NULL,
        telefono VARCHAR(22),
        mail VARCHAR(255) NOT NULL
    )";

    $wpdb->query($sql);
}
function desactivar() {
    flush_rewrite_rules();
}

/// Funciones de activación y desactivación del plugin
register_activation_hook(__FILE__, 'activar');
register_deactivation_hook(__FILE__, 'desactivar');

add_action('admin_menu', 'crearMenu');

// FUNCIONES DEL MENÚ
function crearMenu() {
    add_menu_page(
        'Tabla de usuarios', // Titulo
        'Usuarios', // Titulo del menu
        'manage_options', // Capability 
        'sp_menu', // Slug
        'administracion', // invocar función
        plugin_dir_url(__FILE__).'assets/img/icon.png', // icono
        '1' // posición  
    );

    add_submenu_page(
        'sp_menu', // Parent slug
        'Agregar usuario', // titulo página
        'Administración', // titulo subMenu
        'manage_options', // Capability 
        'sp_menu_usuarios', // Slug
        'usuarios' // invocar función
    );
}
function administracion() {
    // Esta función llama a lista_usuarios.php
    $template_path = plugin_dir_path(__FILE__) . 'templates/lista_usuarios.php';

    // Verificar si el archivo de plantilla existe antes de incluirlo
    if (file_exists($template_path)) {
        include $template_path; // Incluye la plantilla
    } else {
        echo "<p>La plantilla lista_usuarios.php no se ha encontrado.</p>";
    }
}

function usuarios(){
    // Esta función llama a lista_usuarios.php
    $template_path = plugin_dir_path(__FILE__) . 'templates/administrar.php';

    // Verificar si el archivo de plantilla existe antes de incluirlo
    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo "<p>La plantilla lista_usuarios.php no se ha encontrado.</p>";
    }
}

// AGREGAR BOOTSTRAP
function AgregarBootstrapJS($hook) {
    //echo "<script>console.log('$hook')</script>";
    
    if ($hook != "toplevel_page_sp_menu" && $hook != "usuarios_page_sp_menu_usuarios"){
        return ;
    }
    wp_enqueue_script('bootstrapJs', plugins_url('assets/bootstrap/js/bootstrap.min.js', __FILE__), array('jquery'));
}
add_action('admin_enqueue_scripts', 'AgregarBootstrapJS');

function AgregarBootstrapCSS($hook) {
    if ($hook != "toplevel_page_sp_menu" && $hook != "usuarios_page_sp_menu_usuarios"){
        return ;
    }
    wp_enqueue_style('bootstrapCss', plugins_url('assets/bootstrap/css/bootstrap.min.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'AgregarBootstrapCSS');

function agregarJS($hook){
    if ($hook != "toplevel_page_sp_menu" && $hook != "usuarios_page_sp_menu_usuarios"){
        return ;
    }
    wp_enqueue_script('JsExterno', plugins_url('assets/js/aplication.js', __FILE__), array('jquery'));
    // AGREGAR AJAX PARA SOLICITUDES
    wp_localize_script('JsExterno', 'SolicitudesAjax', [
        'url' => admin_url('admin-ajax.php'),
        'seguridad' => wp_create_nonce('seg')
    ]);
}
add_action('admin_enqueue_scripts', 'agregarJS');

// FUNCIONES DEL CRUD
function eliminarUsuario(){
    $nonce = $_POST['nonce'];
    if(!wp_verify_nonce($nonce, 'seg')){
        die('no tiene permisos para ejecutar ese ajax');
    }

    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}usuarios";
    $wpdb->delete($tabla, array('userid' =>$id));
    return true;
}
add_action('wp_ajax_peticioneliminar', 'eliminarUsuario');

function expandirUsuario(){
    $nonce = $_POST['nonce'];
    if(!wp_verify_nonce($nonce, 'seg')){
        die('No tiene permisos para ejecutar ese ajax');
    }

    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}usuarios";
    $usuario = $wpdb->get_row("SELECT * FROM $tabla WHERE userid = $id", ARRAY_A);

    if ($usuario) {
        wp_send_json_success($usuario);
    } else {
        wp_send_json_error('No se pudo encontrar el usuario.');
    }
}
add_action('wp_ajax_expandirUsuario', 'expandirUsuario');

function editarUsuario(){
    $nonce = $_POST['nonce'];
    if(!wp_verify_nonce($nonce, 'seg')){
        wp_send_json_error('No tiene permisos para ejecutar ese ajax.');
    }

    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}usuarios";
    $usuario = $wpdb->get_row("SELECT * FROM $tabla WHERE userid = $id", ARRAY_A);

    if ($usuario) {
        wp_send_json_success($usuario);
    } else {
        wp_send_json_error('No se pudo encontrar el usuario.');
    }
}
add_action('wp_ajax_editarUsuario', 'editarUsuario');

// BUTTON DE CONFIRMAR EDITAR
function guardarCambiosUsuario() {
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'seg')) {
        wp_send_json_error('No tiene permisos para ejecutar ese ajax.');
    }

    $userid = $_POST['userid'];
    $username = sanitize_text_field($_POST['username']);
    $nombre_apellido = sanitize_text_field($_POST['nombre_apellido']);
    $dni = sanitize_text_field($_POST['dni']);
    $telefono = sanitize_text_field($_POST['telefono']);
    $mail = sanitize_email($_POST['mail']);

    global $wpdb;
    $tabla = "{$wpdb->prefix}usuarios";

    $updated = $wpdb->update(
        $tabla,
        array(
            'username' => $username,
            'nombre_apellido' => $nombre_apellido,
            'dni' => $dni,
            'telefono' => $telefono,
            'mail' => $mail
        ),
        array('userid' => $userid),
        array('%s', '%s', '%s', '%s'),
        array('%d')
    );

    if ($updated !== false) {
        wp_send_json_success('Cambios guardados correctamente.');
    } else {
        wp_send_json_error('Error al guardar los cambios.');
    }
}
add_action('wp_ajax_guardarCambiosUsuario', 'guardarCambiosUsuario');
add_action('wp_ajax_nopriv_guardarCambiosUsuario', 'guardarCambiosUsuario'); // Para usuarios no autenticados

