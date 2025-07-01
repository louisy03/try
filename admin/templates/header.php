<?php

if(isset($_POST['cerrar'])){
    //Cerrar sesion
    $_SESSION = array();
    session_destroy();
    header("Location: ../login.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-800 text-white">

<div class="flex flex-col mb-10">
    <!-- Menú -->
    <div class="bg-gray-600 border-b border-gray-300 p-4 flex items-center justify-between">
        <p class="text-lg font-bold">Panel de Administrador</p>
        <div class="flex-grow text-center space-x-4">
            <a href="listaValidaciones.php" class="text-gray-400 hover:text-gray-100 transition duration-300 ease-in-out">Validaciones</a>
            <a href="listaQuejas.php" class="text-gray-400 hover:text-gray-100 transition duration-300 ease-in-out">Reportes</a>
        </div>
        <form action="" method="POST">
            <button type="submit" class="bg-gray-900 text-gray-100 py-2 px-4 rounded-b border-b-2 border-red-500 hover:bg-red-500 hover:border-red-600 transition duration-300 ease-in-out" name="cerrar">Cerrar Sesión</button>
        </form>
    </div>
</div>