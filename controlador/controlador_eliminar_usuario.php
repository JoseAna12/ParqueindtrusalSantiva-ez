<?php
require '../config/conexion.php'; // Asegúrate de que este archivo existe y está bien configurado

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id_usuario = intval($_POST['id']);

        // Verificar que el ID no esté vacío
        if ($id_usuario > 0) {
            $query = "DELETE FROM usuarios WHERE id_usuario = ?";
            $stmt = $conexion->prepare($query);

            if ($stmt) {
                $stmt->bind_param("i", $id_usuario);
                if ($stmt->execute()) {
                    echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente."]);
                } else {
                    echo json_encode(["success" => false, "message" => "Error al eliminar usuario."]);
                }
                $stmt->close();
            } else {
                echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "ID de usuario no válido."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ID de usuario no recibido."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>
