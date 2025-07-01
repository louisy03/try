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
    <title>Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<!-- Menú principal -->
<div class="bg-green-500 text-white py-4 px-10 flex justify-between items-center ">
    <!-- Nombre de la página -->
    <h1 class="text-2xl font-bold">Nombre</h1>
    <!-- Enlaces principales -->
    <div class="text-lg flex justify-center items-center space-x-8">
        <a href="index.php" class="text-gray-300 hover:text-white">Inicio</a>
        <a href="buscar.php" class="text-gray-300 hover:text-white">Buscar</a>
    </div>
    <!-- Menú desplegable -->
    <div class="relative inline-block text-left">
        <div>
            <!-- Imagen que abre el menú -->
            <img class="cursor-pointer h-8 w-8" src="menu-icon.png" alt="Menú" onclick="toggleMenu()">
        </div>
        <!-- Menú desplegable oculto -->
        <div id="menu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
            <div class="py-1" role="none">
                <!-- Enlaces del menú desplegable -->
                <a href="perfil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ir a perfil</a>
                <a href="chats.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ir a chats</a>
                <form action="" method="post">
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" name="cerrar" role="menuitem">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</div>