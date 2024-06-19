<?php
    require_once 'User.php';
    header('Content-Type: application/json');

    $action = $_GET['action'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['action'])) {
            $action = $input['action'];
        }
    }

    if (!$action) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit;
    }

    $user = new User();

    try {
        switch ($action) {
            case 'addUserToGroup':
                $userId = $input['userId'];
                $groupId = $input['groupId'];
                $user->addUserToGroup($userId, $groupId);
                echo json_encode(['status' => 'success']);
                break;
            
            case 'removeUserFromGroup':
                $userId = $input['userId'];
                $groupId = $input['groupId'];
                $user->removeUserFromGroup($userId, $groupId);
                echo json_encode(['status' => 'success']);
                break;

            case 'getUserPermissions':
                $userId = $_GET['userId'];
                $permissions = $user->getUserPermissions($userId);
                echo json_encode(['status' => 'success', 'permissions' => $permissions]);
                break;

            case 'addUserToBlocked':
                $userId = $input['userId'];
                $permissionId = $input['permissionId'];
                $user->addUserToBlocked($userId, $permissionId);
                echo json_encode(['status' => 'success']);
                break;

            case 'removeUserFromBlocked':
                $userId = $input['userId'];
                $permissionId = $input['permissionId'];
                $user->removeUserFromBlocked($userId, $permissionId);
                echo json_encode(['status' => 'success']);
                break;

            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
?>