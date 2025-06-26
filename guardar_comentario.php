<?php
// Variables de conexión deben tener comillas
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guardarcomentarios";

$pagina = "general"; 
$seccion = isset($_POST['seccion']) ? $_POST['seccion'] : '';
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$tema = isset($_POST['tema']) ? trim($_POST['tema']) : '';
$comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

// Validación más robusta
if (empty($seccion) || empty($nombre) || empty($comentario)) {
    header("Location: comentarios.html?comentario=vacio");
    exit();
}

try {
    // Conexión a la base de datos
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'utf8'");
    
    // Consulta preparada con marcadores de posición
    $stmt = $conn->prepare("INSERT INTO comentarios (pagina, seccion, nombre, email, tema, comentario, fecha) 
                           VALUES (:pagina, :seccion, :nombre, :email, :tema, :comentario, NOW())");
    
    // Vincular parámetros
    $stmt->bindParam(':pagina', $pagina, PDO::PARAM_STR);
    $stmt->bindParam(':seccion', $seccion, PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':tema', $tema, PDO::PARAM_STR);
    $stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);
    
    // Ejecutar consulta
    $stmt->execute();
    
    // Redireccionar con mensaje de éxito
    header("Location: comentarios.html?comentario=exito");
    exit();
    
} catch(PDOException $e) {
    // Registrar error y redireccionar
    error_log("Error al guardar comentario: " . $e->getMessage());
    header("Location: comentarios.html?comentario=error&mensaje=" . urlencode($e->getMessage()));
    exit();
}

// Cerrar conexión (se cierra automáticamente al final del script)
$conn = null;
?>