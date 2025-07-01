<?php
function registrarUsuario($datos) {
    include('bd.php');
    // Obtener otros campos
    $nombre_completo = $datos['nombre_apellidos'];
    $correo_electronico = $datos['correo_electronico'];
    $numero_telefono = $datos['numero_telefono'];
    $codigo_estudiante = $datos['codigo_estudiante'];
    $contrasena = password_hash($datos['password'], PASSWORD_DEFAULT);

    // Guardar las imágenes en una carpeta del servidor
    $ruta_foto_perfil = guardarImagen($_FILES['foto_perfil']);
    $ruta_foto_credencial = guardarImagen($_FILES['foto_credencial']);

    // Verificar si el correo electrónico ya está en uso
    $query_correo = "SELECT COUNT(*) FROM usuarios WHERE correo = $1";
    $result_correo = pg_query_params($conexion, $query_correo, array($correo_electronico));
    $row_correo = pg_fetch_assoc($result_correo);
    $correo_existente = intval($row_correo['count']) > 0;

    // Verificar si el código de estudiante ya está en uso
    $query_codigo = "SELECT COUNT(*) FROM usuarios WHERE codigo_estudiante = $1";
    $result_codigo = pg_query_params($conexion, $query_codigo, array($codigo_estudiante));
    $row_codigo = pg_fetch_assoc($result_codigo);
    $codigo_existente = intval($row_codigo['count']) > 0;

    $errores = "";

    if ($correo_existente && $codigo_estudiante) {
        $errores .= "❌El correo electrónico ya ha sido registrado.<br>";
        $errores .= "❌El código de estudiante ya ha sido registrado.<br>";
        return $errores;
    }else if ($correo_existente){
        $errores .= "❌El correo electrónico ya ha sido registrado.<br>";
        return $errores;
    }else if ($codigo_existente) {
        $errores .= "❌El código de estudiante ya ha sido registrado.<br>";
        return $errores;
    }else{
        $query_insert = "INSERT INTO usuarios (nombre_completo, correo, foto_perfil, telefono, codigo_estudiante, foto_credencial, contraseña) VALUES ($1, $2, $3, $4, $5, $6, $7)";
        $result_insert = pg_query_params($conexion, $query_insert, array($nombre_completo, $correo_electronico, $ruta_foto_perfil, $numero_telefono, $codigo_estudiante, $ruta_foto_credencial, $contrasena));
        
        if ($result_insert) {
            header("Location: /Libros/login.php");
        }
    }
}

// Función para guardar la imagen en una carpeta del servidor y retornar la ruta
function guardarImagen($imagen) {
    $ruta_destino = "./user/img/";
    $nombre_archivo = uniqid() . "_" . $imagen['name'];
    $ruta_completa = $ruta_destino . $nombre_archivo;
    move_uploaded_file($imagen['tmp_name'], $ruta_completa);
    return $ruta_completa;
}
$errores = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registro'])) {
    $errores = registrarUsuario($_POST);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="css/output.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex justify-center items-center h-screen body-login-register">
    <!--Formulario-->
    <div class="relative overflow-hidden bg-white shadow-md rounded m-24 w-1/3 max-w-full flex">
    <div class="bg-gray-800 w-1/12"></div>
    <div class="px-16 py-8 flex-grow w-11/12 max-w-full container"> 
        <h1 class="text-2xl font-semibold bg-gray-800  text-white p-2 text-center titulo no-underline rounded-3xl">Registrarse</h1>
            <form action="#" method="POST"  onsubmit="return validarFormulario()" enctype="multipart/form-data">
                <div class="grid grid-cols-2 gap-4 mb-4 mt-5">
                    <!-- Fotos -->
                    <div class="mb-4 ">
                        <label class="block text-gray-700 text-md font-bold mb-2" for="foto_perfil">
                            Foto de perfil
                        </label>
                        <div class="image-upload" ondrop="soltarArchivo(event, 'img_perfil')" ondragover="arrastrarSobre(event)">
                            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" onchange="cambiarArchivo(event, 'img_perfil', 'icon_perfil')">
                            <i id="icon_perfil" class="fas fa-camera"></i>
                            <img id="img_perfil" src="#" alt="Foto de perfil" style="display: none;">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-md font-bold mb-2" for="foto_credencial">
                            Foto de la credencial
                        </label>
                        <div class="image-upload" ondrop="soltarArchivo(event, 'img_credencial')" ondragover="arrastrarSobre(event)">
                            <input type="file" id="foto_credencial" name="foto_credencial" accept="image/*" onchange="cambiarArchivo(event, 'img_credencial','icon_credencial')">
                            <i id="icon_credencial" class="fas fa-camera"></i>
                            <img id="img_credencial" src="#" alt="Foto de la credencial de estudiante" style="display: none;">
                        </div>
                    </div>
                    <!-- Otros datos-->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-md font-bold mb-2" for="nombre_apellidos">
                            Nombre(s) y Apellido(s)
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="nombre_apellidos" id="nombre_apellidos" type="text" placeholder="Nombre y apellido">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-md font-bold mb-2" for="correo_electronico">
                            Correo institucional
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="correo_electronico" id="correo_electronico" type="email" placeholder="ejemplo@alumnos.udg.mx">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-md font-bold mb-2" for="numero_telefono">
                            Telefono
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="numero_telefono" name="numero_telefono" type="text" placeholder="10 digitos">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-md font-bold mb-2" for="codigo_estudiante">
                            Codigo de estudiante
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="codigo_estudiante" name="codigo_estudiante" type="text" placeholder="9 digitos">
                    </div>
                    <div class="mb-4 ">
                        <label class="block text-gray-700 text-md font-bold mb-2" for="password">
                            Contraseña
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" placeholder="**********">
                    </div>
                    <div class="mb-4 ">
                        <label class="block text-gray-700 text-md font-bold mb-2" for="password2">
                            Confirmar contraseña
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password2" type="password" name="password2" placeholder="**********">
                    </div>
                    
                </div>
                <div class="mb-4">
                    <p class=" text-sm font-bold mb-2 text-red-600" id="Error"></p>

                    <?php if(isset($errores)){ ?>
                        <p class=" text-sm font-bold mb-2 text-red-600"><?php echo $errores; ?></p>
                    <?php } ?>
                    
                </div>
                <div class="mb-6">
                    <a href="login.php" class="text-sm font-bold mb-2 no-underline text-blue-300">¿Ya tienes cuenta? Iniciar sesión</a>
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" name="registro" type="submit">
                        Registrarse
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
