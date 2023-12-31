<!DOCTYPE html>
<html lang="en">
<?php
function obtenerCoordenadas($idSeleccionado) {
    
    include "modelo/conexion.php";
    $coordenadas = [];
    $query = "SELECT latitu, longi FROM coordenadas_Marselo WHERE idcoordenadas = $idSeleccionado";
    $registros = mysqli_query($conexion, $query) or die("No se pudo leer los datos: " . mysqli_error($conexion));
    mysqli_close($conexion);
    if ($registros->num_rows > 0) {
        while ($row = $registros->fetch_assoc()) {
            $coordenadas[] = [$row["latitu"], $row["longi"]];
        }
    }
    return $coordenadas;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idSeleccionado = $_POST["seleccionar_persona"];
    echo "El ID seleccionado es: $idSeleccionado";
    
    $coordenadas = obtenerCoordenadas($idSeleccionado);
    
    foreach ($coordenadas as $coord) {
        echo $coord[0] . ", " . $coord[1];
    }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Mapa con Mapbox</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
    <link
        href="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css"
        rel="stylesheet"
    />
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            width: 100%;
            height: 1000px;
        }
    </style>
</head>
<body>

    <div>
        <a href="index.php">Cambiar Usuario</a>
    </div>
    <div id="map"></div>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiNGx2YXJvLWc0IiwiYSI6ImNsa3Y3aGZuejBxYTYzbG1xemxkemFvbXYifQ.iFYtfFcNe7flCJWvzOG8lA'; // Reemplaza con tu propio token de Mapbox
        <?php
            $coordenadas = obtenerCoordenadas($idSeleccionado);
        ?>
         var latitud = <?php echo $coord[0]; ?>;
        var longitud = <?php echo $coord[1]; ?>;
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11', // Puedes cambiar el estilo del mapa
            center: [ longitud, latitud], // Coordenadas de centro del mapa (longitud, latitud)
            zoom: 15
            });
            function cargarMarcadores() {
            <?php
            $coordenadas = obtenerCoordenadas($idSeleccionado);
            foreach ($coordenadas as $coord) {
                echo "new mapboxgl.Marker().setLngLat([" . $coord[1] . ", " . $coord[0] . "]).addTo(map);\n";
            }
            ?>
        }

        // Llama a la función para cargar los marcadores al cargar la página
        cargarMarcadores();

        // Función para actualizar los marcadores cada 5 segundos
        function actualizarMarcadores() {
            cargarMarcadores();
            setTimeout(actualizarMarcadores, 5000); // Llama a esta función nuevamente después de 5 segundos
        }

        // Inicia la actualización de los marcadores
        setTimeout(actualizarMarcadores, 5000);
    </script>
</body>
</html>


