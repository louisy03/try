<?php
    include("../bd.php");
    if(isset($_GET['txtID'])){
        $id = $_GET['txtID'];

        // Consultar los detalles del usuario con el id proporcionado
        $query = "SELECT * FROM reportes WHERE id = $1";
        $resultado = pg_query_params($conexion, $query, array($id));

        // Verificar si se encontró algún reporte con el id proporcionado
        if($resultado && pg_num_rows($resultado) > 0) {
            // Obtener los detalles del reporte
            $reporte = pg_fetch_assoc($resultado);
        }
    }

//Funciones para actualizar
function actualizarEstadoReporte($conexion, $id, $estado) {
    $query_update = "UPDATE reportes SET estado = $1 WHERE id = $2";
    $result_update = pg_query_params($conexion, $query_update, array($estado, $id));
    return $result_update;
}
 
function actualizarStrikesUsuario($conexion, $id, $strikes) {
    $query_update = "UPDATE usuarios SET strikes = $1 WHERE id = $2";
    $result_update = pg_query_params($conexion, $query_update, array($strikes, $id));
    return $result_update;
}

function actualizarBaneadoUsuario($conexion, $id, $baneado, $baneado_permanente) {
    $query_update = "UPDATE usuarios SET baneado = $1, baneado_permanente = $2 WHERE id = $3";
    $result_update = pg_query_params($conexion, $query_update, array($baneado, $baneado_permanente, $id));
    return $result_update;
}

// Cambiar estado de usuario a usuario registrado
if(isset($_POST['rechazar']) && isset($reporte)) {
    $resultado = actualizarEstadoReporte($conexion, $id, 'Completado');
    if($resultado) {
        header("Location: listaQuejas.php");
        exit;
    } else {
        $mensaje = "Error al validar al usuario.";
    }
}

// Cambiar cantidad de strike a usuario reportado
if(isset($_POST['strike']) && isset($reporte)) {
    $resultado = actualizarEstadoReporte($conexion, $id, 'Completado');
    $usuario_reportado_id = $reporte['usuario_reportado_id'];

    // Obtener el número actual de strikes del usuario reportado
    $query_strikes = "SELECT strikes FROM usuarios WHERE id = $1";
    $resultado_strikes = pg_query_params($conexion, $query_strikes, array($usuario_reportado_id));
    $strikes = pg_fetch_assoc($resultado_strikes)['strikes'];

    // Aumentar el número de strikes en 1
    $nuevo_strikes = $strikes + 1;

    // Actualizar los strikes del usuario reportado en la base de datos
    $resultado = actualizarStrikesUsuario($conexion, $usuario_reportado_id, $nuevo_strikes);

    if($resultado) {
        // Verificar si el usuario tiene 3 o más strikes
        if($nuevo_strikes >= 3) {
            // Si tiene 3 o más strikes, se le marca como baneado
            $resultado = actualizarBaneadoUsuario($conexion, $usuario_reportado_id, true, false);
        }
        header("Location: listaQuejas.php");
        exit;
    } else {
        $mensaje =  "Error al rechazar al usuario.";
    }
}


// Cambiar estado de usuario a baneado por 3 dias
if(isset($_POST['baneado']) && isset($reporte)) {
    $resultado = actualizarEstadoReporte($conexion, $id, 'Completado');
    $usuario_reportado_id = $reporte['usuario_reportado_id'];

    // Actualizar el estado de baneado del usuario reportado a true y baneado_permanente a false (3 días de ban)
    $resultado = actualizarBaneadoUsuario($conexion, $usuario_reportado_id, true, false);
    
    if($resultado) {
        header("Location: listaQuejas.php");
        exit;
    } else {
        $mensaje =  "Error al rechazar al usuario.";
    }
}

// Cambiar estado de usuario a baneado permanente
if(isset($_POST['baneado_permanente']) && isset($reporte)) {
    $resultado = actualizarEstadoReporte($conexion, $id, 'Completado');
     // Obtener el ID del usuario reportado
     $usuario_reportado_id = $reporte['usuario_reportado_id'];

     // Actualizar el estado de baneado del usuario reportado a true y baneado_permanente a true (ban permanente)
     $resultado = actualizarBaneadoUsuario($conexion, $usuario_reportado_id, false, true);
     
    if($resultado) {
        header("Location: listaQuejas.php");
        exit;
    } else {
        $mensaje =  "Error al rechazar al usuario.";
    }
}
?>




<?php include("./templates/header.php"); ?>


<div class="container mx-auto mt-10 max-w-3xl">
    <h2 class="text-2xl font-semibold mb-4 text-center">Validar Reporte</h2>
    <?php if(isset($reporte)) { ?>
        <form action="" method="POST" class="bg-gray-300 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <input type="hidden" name="id" value="<?php echo $reporte['id']; ?>">
            <div class="flex flex-wrap -mx-4 mb-4">
                <div class="w-full md:w-1/2 px-4 mb-4 md:mb-0">
                    <label for="nombre_completo" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Reportante</label>
                    <input type="text" name="nombre_completo" id="nombre_completo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $reporte['usuario_reportante']; ?>" readonly>
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <label for="correo" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Reportado</label>
                    <input type="email" name="correo" id="correo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $reporte['usuario_reportado']; ?>" readonly>
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <label for="codigo_estudiante" class="block text-gray-700 text-sm font-bold mb-2">Descripcion detallada</label>
                    <input type="text" name="codigo_estudiante" id="codigo_estudiante" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $reporte['descripcion']; ?>" readonly>
                </div>
                <div class="w-full md:w-1/2 px-4">
                    <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                    <input type="text" name="telefono" id="telefono" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $reporte['fecha']; ?>" readonly>
                </div>
             </div>
            <div class="flex flex-wrap -mx-4 mb-4">
                <?php if(isset($mensaje)){ ?>
                    <p class=" text-sm font-bold mb-2 text-red-600"><?php echo $mensaje; ?></p>
                <?php } ?>
            </div>
            <div class="flex items-center justify-start">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-3" name="rechazar">Rechazar reporte</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-3" name="strike">Dar Strike</button>
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-3" name="baneado">Banear 3 dias</button>
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-3" name="baneado_permanente">Banear para siempre</button>
             </div>
        </form>
    <?php } else { ?>
        <p class="text-red-500">No se encontró ningún usuario con el id proporcionado.</p>
    <?php } ?>
</div>



<?php include("./templates/footer.php"); ?>