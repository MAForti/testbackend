<?php

require_once 'db.php'; // Incluir el archivo db.php

// Definir la clase UserController
class UserController {
    private $dbController;

    public function __construct(DatabaseController $dbController) {
        $this->dbController = $dbController;
    }

    public function createUser($fullname, $email, $pass, $openid) {
        $data = array(
            'fullname' => $fullname,
            'email' => $email,
            'pass' => $pass,
            'openid' => $openid
        );
        return $this->dbController->create('user', $data); // Crear un nuevo usuario en la tabla 'user'
    }

    public function getUser($userId) {
        return $this->dbController->read('user', $userId); // Obtener información de un usuario por su ID
    }

    public function updateUser($userId, $newData) {
        return $this->dbController->update('user', $userId, $newData); // Actualizar información de un usuario por su ID
    }

    public function deleteUser($userId) {
        return $this->dbController->delete('user', $userId); // Eliminar un usuario por su ID
    }
}

// Crear una instancia de DatabaseController
$dbController = new DatabaseController();

// Crear una instancia de UserController
$userController = new UserController($dbController);

// Manejar la solicitud según el método HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $pass = isset($_POST['pass']) ? $_POST['pass'] : null;
        $openid = isset($_POST['openid']) ? $_POST['openid'] : $openid = uniqid(); // Genera un ID único basado en la hora actual;
        if ($fullname != null && $email != null && $pass != null) {
            if (isset($_GET['id'])) { 
                // Agregar un nuevo usuario
                $response = $userController->createUser($fullname, $email, $pass, $openid);
            } else {
            // Modificar Usuario   
                $newData = array(
                    'fullname' => $fullname,
                    'email' => $email,
                    'pass' => $pass,
                    'openid' => $openid
                );
                $response = $userController->updateUser($userId, $newData);
            } 
            $response = 'Faltan datos requeridos';
        } 
        break;
    case 'GET':
        // Obtener información de un usuario por su ID
        $userId = isset($_GET['id']) ? $_GET['id'] : 0;
        $response = $userController->getUser($userId);
        $rows = array();
        while ($row = $response->fetch_assoc()) {
            $rows[] = $row;
        }
        $response = $rows;
        break;
    case 'DELETE':
        // Eliminar un usuario por su ID
        $userId = isset($_GET['id']) ? $_GET['id'] : 0;
        if ($userId != 0) {
            $response = $userController->deleteUser($userId);
        } else {
            $response = 'No se especifico el usuario a borrar';
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