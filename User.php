<?php

    require 'db_config.php';

    class User
    {
        private PDO $db;

        public function __construct()
        {
            $this->db = getDB();
        }

        public function addUserToGroup(int $userId, int $groupId): void
        {
            $stmt = $this->db->prepare("INSERT INTO user_groups (user_id, group_id) VALUES (?, ?)");
            $stmt->execute([$userId, $groupId]);
        }

        public function removeUserFromGroup(int $userId, int $groupId): void
        {
            $stmt = $this->db->prepare("DELETE FROM user_groups WHERE user_id = ? AND group_id = ?");
            $stmt->execute([$userId, $groupId]);
        }

        public function getUserPermissions($userId)
        {
            // Получаем права пользователя из всех групп, в которые он входит
            $stmt = $this->db->prepare("
                SELECT p.name
                FROM permissions p
                JOIN group_permissions gp ON p.id = gp.permission_id
                JOIN user_groups ug ON gp.group_id = ug.group_id
                WHERE ug.user_id = ?
            ");
            $stmt->execute([$userId]);
            $userPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            // Получаем права, которые были заблокированы для пользователя
            $stmt = $this->db->prepare("
                SELECT p.name
                FROM permissions p
                JOIN blocked_users bu ON p.id = bu.permission_id
                WHERE bu.user_id = ?
            ");
            $stmt->execute([$userId]);
            $blockedPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
            // Объединяем права пользователя, исключая заблокированные права
            $allPermissions = ['send_messages', 'service_api', 'debug'];
            $finalPermissions = [];
            foreach ($allPermissions as $permission) {
                if (in_array($permission, $userPermissions) && !in_array($permission, $blockedPermissions)) {
                    $finalPermissions[$permission] = 'yes';
                } else {
                    $finalPermissions[$permission] = 'no';
                }
            }
        
            return $finalPermissions;
        }

        public function addUserToBlocked(int $userId, int $permissionId): void
        {
            $stmt = $this->db->prepare("INSERT INTO blocked_users (user_id, permission_id) VALUES (?, ?)");
            $stmt->execute([$userId, $permissionId]);
        }

        public function removeUserFromBlocked(int $userId, int $permissionId): void
        {
            $stmt = $this->db->prepare("DELETE FROM blocked_users WHERE user_id = ? AND permission_id = ?");
            $stmt->execute([$userId, $permissionId]);
        }
    }
?>