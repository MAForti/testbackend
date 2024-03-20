<?php

require_once 'db.php'; // Incluir el archivo db.php

// Definir la clase CommentController
class CommentController {
    private $dbController;

    public function __construct(DatabaseController $dbController) {
        $this->dbController = $dbController;
    }


    public function createComment($user, $coment_text) {
        $data = array(
            'user' => $user,
            'coment_text' => $coment_text,
        );
        return $this->dbController->create('comment_controller', $data); // Crear un nuevo comentario en la tabla 'comment'
    }

    public function getComment($commentId) {
        return $this->dbController->read('comment_controller', $commentId); // Obtener información de un comentario por su ID
    }

    public function updateComment($commentId, $newData) {
        return $this->dbController->update('comment_controller', $commentId, $newData); // Actualizar información de un comentario por su ID
    }

    public function deleteComment($commentId) {
        return $this->dbController->delete('comment_controller', $commentId); // Eliminar un comentario por su ID
    }
}

// Crear una instancia de DatabaseController
$dbController = new DatabaseController();

// Crear una instancia de commentController
$commentController = new commentController($dbController);

// Manejar la solicitud según el método HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $user = isset($_POST['user']) ? $_POST['user'] : null;
        $comment_text = isset($_POST['comment_text']) ? $_POST['comment_text'] : null;
        if ($user != null) {
            if (isset($_GET['id'])) { 
                // Agregar un nuevo comentario
                $response = $commentController->createUser($user, $comment_text);
            } else {
            // Modificar comentario   
                $newData = array(
                    'user' => $fullname,
                    'comment_text' => $email,
                );
                $response = $commentController->updateUser($_GET['id'], $newData);
            } 
            $response = 'Faltan datos requeridos';
        } 
        break;
    case 'GET':
        // Obtener información de un comentario por su ID
        $commentId = isset($_GET['id']) ? $_GET['id'] : 0;
        $response = $commentController->getComment($commentId);
        $rows = array();
        while ($row = $response->fetch_assoc()) {
            $rows[] = $row;
        }
        $response = $rows;
        break;
    case 'DELETE':
        // Eliminar un comentario por su ID
        $CommentId = isset($_GET['id']) ? $_GET['id'] : 0;
        if ($CommentId != 0) {
            $response = $commentController->deleteComment($CommentId);
        } else {
            $response = 'No se especifico el comentario a borrar';
        }
        break;
    default:
        $response = 'Método no permitido';
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;

?>