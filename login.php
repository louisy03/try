<?php


function iniciarSesion($codigo_estudiante, $contrasena) {
    include('bd.php');
    
    $query = "SELECT * FROM usuarios WHERE codigo_estudiante = $1";
    $result = pg_query_params($conexion, $query, array($codigo_estudiante));
    $row = pg_fetch_assoc($result);

    if ($row && password_verify($contrasena, $row['contraseña'])) {
        // Verificar si el usuario está baneado 
        echo $row['baneado'];
        echo $row['baneado_permanente'];
        if ($row['baneado'] == "t" ) {
            header("Location: /Libros/user/baneado.php");
            exit;
        }

        // Verificar si el usuario está baneado permanentemente
        if ($row['baneo_permanente'] == "t" ) {
            header("Location: /Libros/user/baneadopermanente.php");
            exit;
        }

        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['logueado'] = true;

        switch ($row['estado']) {
            case 'usuario no registrado':
                header("Location: /Libros/user/perfil.php");
                break;
            case 'usuario registrado':
                header("Location: /Libros/user/index_user.html");
                break;
            case 'administrador':
                header("Location: /Libros/admin/listaValidaciones.php");
                break;
        }
        exit;
    } else {
        return "Código de estudiante o contraseña incorrectos.";
    }
    
    pg_close($conexion);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $error = iniciarSesion($_POST['codigo_estudiante'], $_POST['contrasena']);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/output.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <title>Login</title>
  <style>
    /* Estilos adicionales */
    .container {
      width: 90%; /* Ancho del contenedor principal */
    }
  </style>
</head>
<body class="flex justify-center items-center h-screen body-login-register">
    <div class="relative overflow-hidden bg-white shadow-md rounded m-24 w-1/4 max-w-full flex">
        <div class="bg-gray-800 w-1/12"></div>
        <div class="px-16 py-8 flex-grow w-11/12 max-w-full container">
            <h1 class="text-2xl font-semibold bg-gray-800  text-white p-2 text-center titulo no-underline rounded-3xl">Iniciar sesión</h1>
            <form action="#" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-md font-bold mb-2 mt-4" for="codigo_estudiante">
                        Codigo
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="codigo_estudiante" name="codigo_estudiante"  type="number" placeholder="10 digitos" >
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-md font-bold mb-2" for="contrasena">
                        Contraseña
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="contrasena" name="contrasena" type="password" placeholder="***********">
                </div>
                <div class="mb-4">
                    <p class="text-sm font-bold mb-2 text-red-600" id="error"></p>
                    <?php if(isset($error)) { ?>
                        <p class="text-sm font-bold mb-2 text-red-600" id="error"><?php echo $error; ?></p>
                    <?php }?>
                </div>
                <div class="mb-6">
                    <a href="register.php" class="text-sm font-bold mb-2 no-underline text-blue-300">No tienes cuenta? Registrarse</a>
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline " type="submit" name="login">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

<style>
        /* Para Chrome, Safari, Edge y Opera */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Para Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</html>
