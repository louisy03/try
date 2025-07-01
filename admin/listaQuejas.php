<?php
    // Incluir el archivo de conexión a la base de datos
    include("../bd.php");

    // Definir la consulta SQL
    $query = "SELECT * FROM reportes WHERE estado = 'En proceso'";

    // Ejecutar la consulta SQL
    $resultado = pg_query($conexion, $query);

    // Verificar si la consulta fue exitosa
    if (!$resultado) {
        echo "Error al ejecutar la consulta.";
        exit;
    }
?>

<?php include("./templates/header.php"); ?>


<div class="overflow-x-auto mx-auto max-w-5xl rounded-lg">
    <table class="w-full bg-white shadow-md ">
        <thead>
            <tr class="bg-gray-900 text-white">
                <th class="px-4 py-3 text-left">Usuario alta</th>
                <th class="px-4 py-3 text-left">Usuario reportado</th>
                <th class="px-4 py-3 text-left">Descripcion</th>
                <th class="px-4 py-3 text-left">Fecha</th>
                <th class="px-4 py-3 text-left">Accion</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Iterar sobre los resultados de la consulta
            while ($fila = pg_fetch_assoc($resultado)) {
            ?>
                <tr class="hover:bg-gray-100 text-black">
                    <td class="px-4 py-3 border-b"><?php echo $fila['usuario_reportante']; ?></td>
                    <td class="px-4 py-3 border-b"><?php echo $fila['usuario_reportado']; ?></td>
                    <td class="px-4 py-3 border-b"><?php echo $fila['descripcion']; ?></td>
                    <td class="px-4 py-3 border-b"><?php echo $fila['fecha']; ?></td>
                    <td class="px-4 py-3 border-b">
                        <a href="quejas.php?txtID=<?php echo $fila['id']; ?>"class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ver más</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include("./templates/footer.php"); ?>