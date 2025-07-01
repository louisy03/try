<?php
include("../bd.php");

if(isset($_GET['txtID'])){
    $id = $_GET['txtID'];

    // Consultar los detalles del usuario con el id proporcionado
    $query = "SELECT * FROM usuarios WHERE id = $1";
    $resultado = pg_query_params($conexion, $query, array($id));

    // Verificar si se encontró algún usuario con el id proporcionado
    if($resultado && pg_num_rows($resultado) > 0) {
        // Obtener los detalles del usuario
        $usuario = pg_fetch_assoc($resultado);
        // Leer los datos de la imagen de foto_perfil y foto_credencial
        $foto_perfil = $usuario['foto_perfil'];
        $foto_credencial = $usuario['foto_credencial'];
    }
}

//Funcion para actualizar
function actualizarEstadoUsuario($conexion, $id, $estado) {
    $query_update = "UPDATE usuarios SET estado = $1 WHERE id = $2";
    $result_update = pg_query_params($conexion, $query_update, array($estado, $id));
    return $result_update;
}

// Cambiar estado de usuario a usuario registrado
if(isset($_POST['validar']) && isset($usuario)) {
    $resultado = actualizarEstadoUsuario($conexion, $id, 'usuario registrado');
    if($resultado) {
        header("Location: listaValidaciones.php");
        exit;
    } else {
        $mensaje = "Error al validar al usuario.";
    }
}

// Cambiar estado de usuario a usuario rechazado
if(isset($_POST['rechazar']) && isset($usuario)) {
    $resultado = actualizarEstadoUsuario($conexion, $id, 'usuario rechazado');
    if($resultado) {
        header("Location: listaValidaciones.php");
        exit;
    } else {
        $mensaje =  "Error al rechazar al usuario.";
    }
}

?>

<?php include("./templates/header.php"); ?>


<div class="container mx-auto mt-10 max-w-3xl">
    <h2 class="text-2xl font-semibold mb-4 text-center">Validar Usuario</h2>
    <?php if(isset($usuario)) { ?>
        <form action="" method="POST" class="bg-gray-300 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            <div class="flex flex-wrap -mx-4 mb-4">
                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                    <label for="nombre_completo" class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo</label>
                    <input type="text" name="nombre_completo" id="nombre_completo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $usuario['nombre_completo']; ?>" readonly>
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <label for="correo" class="block text-gray-700 text-sm font-bold mb-2">Correo</label>
                    <input type="email" name="correo" id="correo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $usuario['correo']; ?>" readonly>
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <label for="codigo_estudiante" class="block text-gray-700 text-sm font-bold mb-2">Código de Estudiante</label>
                    <input type="text" name="codigo_estudiante" id="codigo_estudiante" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $usuario['codigo_estudiante']; ?>" readonly>
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $usuario['telefono']; ?>" readonly>
                </div>
            </div>
            <div class="flex flex-wrap -mx-4 mb-4">
                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                    <label for="foto_perfil" class="block text-gray-700 text-sm font-bold mb-2">Foto de Perfil</label>
                    <img src="../<?php echo $foto_perfil; ?>" alt="Foto de Perfil" class="w-full h-auto">
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <label for="foto_credencial" class="block text-gray-700 text-sm font-bold mb-2">Foto de Credencial</label>
                    <img src="../<?php echo $foto_credencial; ?>" alt="Foto de Credencial" class="w-full h-auto">
                </div>
            </div>
            <div class="flex flex-wrap -mx-4 mb-4">

                <?php if(isset($mensaje)){ ?>
                    <p class=" text-sm font-bold mb-2 text-red-600"><?php echo $mensaje; ?></p>
                <?php } ?>
            </div>
            <div class="flex items-center justify-start">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-3" name="validar">Validar</button>
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-3" name="rechazar">Rechazar</button>
             </div>
        </form>
    <?php } else { ?>
        <p class="text-red-500">No se encontró ningún usuario con el id proporcionado.</p>
    <?php } ?>
</div>



<?php include("./templates/footer.php"); ?>