
<?php
    include "modelo/conexion.php";
    $registros = mysqli_query($conexion, "select idcoordenadas from coordenadas_Marselo") or die("No se pudo leer los datos: " . mysqli_error($conexion));
    mysqli_close($conexion);
?>

<form method="post" action="index.php"> 
        <label for="seleccionar_persona">Selecciona una persona:</label>
        <select name="seleccionar_persona" id="seleccionar_persona">
            <?php
                while ($row = mysqli_fetch_assoc($registros)) {
                    echo "<option value='{$row['idcoordenadas']}'>{$row['idcoordenadas']}</option>";
                }
            ?>
        </select>
        <br>
        <input type="submit" value="Enviar">
</form>