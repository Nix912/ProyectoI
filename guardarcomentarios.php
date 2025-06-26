<?php
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "nombre_base_datos";

$pagina = "general"; 
$seccion = isset($_POST['seccion']) ? $_POST['seccion'] : '';
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$tema = isset($_POST['tema']) ? trim($_POST['tema']) : '';
$comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

if (empty($seccion) || empty($nombre) || empty($comentario)) {
    header("Location: comentarios.html?comentario=vacio");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'utf8'");
    
    $stmt = $conn->prepare("INSERT INTO comentarios (pagina, seccion, nombre, email, tema, comentario, fecha) 
                           VALUES (:pagina, :seccion, :nombre, :email, :tema, :comentario, NOW())");
    
    $stmt->bindParam(':pagina', $pagina);
    $stmt->bindParam(':seccion', $seccion);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':tema', $tema);
    $stmt->bindParam(':comentario', $comentario);
    
    $stmt->execute();
    
    header("Location: comentarios.html?comentario=exito");
    exit();
    
} catch(PDOException $e) {
    error_log("Error al guardar comentario: " . $e->getMessage());
    
    header("Location: comentarios.html?comentario=error");
    exit();
}

$conn = null;
?>