<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIVOX Image Server</title>
    <!-- Agregar Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: transparent;
        }
        
        a {
            color: black!important;
        }
        
        a:hover {
            text-decoration: none!important;
            color: black!important;
        }
    </style>

</head>
<body>
    <div class="container" style="width: 27rem; margin-top: 0.3rem; background-color: #ececec; padding: 1.4rem; border-radius: 10px; border: solid 3px #4f4f4f;" >
            <center>
                <a href="http://localhost/imgserver/">
                    <img src="http://localhost/imgserver/img.webp" width="200px">
                    <p style="font-size: 32px; font-weight: 700;">DIVOX Image Server</p>
                </a>
            </center>
        <?php
        // Verificar si se ha enviado un archivo
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
            $file = $_FILES['archivo'];

            // Directorio de almacenamiento
            $uploadDirectory = 'uploads/';

            // Validar el tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                echo "<p style='color: red;'>Error: Tipo de archivo no válido.</p>";
                exit;
            }

            // Validar el tamaño del archivo (por ejemplo, 600 kb)
            $maxFileSize = 600 * 1024; // 600 kilobytes


            if ($file['size'] > $maxFileSize) {
                echo "<p style='color: red;'>Error: Tamaño de archivo excede el límite permitido.</p>";
                exit;
            }

            // Generar un nombre único para el archivo cargado
            $fileName = uniqid('image_') . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

            // Mover el archivo al directorio de almacenamiento
            if (move_uploaded_file($file['tmp_name'], $uploadDirectory . $fileName)) {
                $baseURL = 'http://localhost/imgserver/';
                $imageURL = $baseURL . $uploadDirectory . $fileName; // Modificado para incluir "imgServer/"
                echo "<p style='color: green;'>Subida exitosa a la siguiente URL: <input style='width: 100%;' type='text' id='imageURL' value='$imageURL' readonly></p>";
                echo "<button class='btn btn-success' onclick='copyToClipboard(this)'><i class='fas fa-copy'></i> Copiar URL</button><br><br><br>";
            } else {
                echo "<p style='color: red;'>Error al cargar el archivo.</p>";
            }
        }

        // Función JavaScript para copiar la URL al portapapeles
        ?>
        <script>
        function copyToClipboard(btn) {
            var copyText = document.getElementById("imageURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
            // Cambiar el contenido del botón a "Copiado" y deshabilitarlo
            btn.innerHTML = "<i class='fas fa-check'></i> Copiado";
            btn.disabled = true;
        }

        // Función para actualizar el nombre del archivo seleccionado en el input
        function updateFileName(input) {
            var fileName = input.files[0].name;
            var label = input.nextElementSibling;
            label.innerText = fileName;
        }
        </script>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile" name="archivo" accept="image/*" required onchange="updateFileName(this)">
                    <label class="custom-file-label" for="inputGroupFile">No se seleccionó un archivo.</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" ><i class="fa-solid fa-arrow-up-from-bracket"></i>Subir Imagen</button>
        </form>
    </div>
    <!-- Agregar Bootstrap JS (opcional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

