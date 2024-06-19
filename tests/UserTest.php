<?php

    use PHPUnit\Framework\TestCase;

    require_once __DIR__ . '/../User.php';
    require_once __DIR__ . '/../db_config.php';

    class UserTest extends TestCase
    {
        private $user;
        private $db;

        protected function setUp(): void
        {
            $this->db = getDB();
            $this->user = new User();
            
            // Очистка всех данных из таблиц в правильном порядке
            $this->db->exec("SET FOREIGN_KEY_CHECKS=0");
            $this->db->exec("TRUNCATE TABLE blocked_users");
            $this->db->exec("TRUNCATE TABLE user_groups");
            $this->db->exec("TRUNCATE TABLE users");
            $this->db->exec("TRUNCATE TABLE groups");
            $this->db->exec("TRUNCATE TABLE permissions");
            $this->db->exec("SET FOREIGN_KEY_CHECKS=1");
            
            // Добавление данных для тестов
            $this->db->exec("INSERT INTO users (id, username) VALUES (1, 'user1'), (2, 'user2'), (3, 'user3')");
            $this->db->exec("INSERT INTO permissions (id, name) VALUES (1, 'send_messages'), (2, 'service_api'), (3, 'debug')");
            $this->db->exec("INSERT INTO groups (id, name) VALUES (1, 'group1'), (2, 'group2')");
            $this->db->exec("INSERT INTO group_permissions (group_id, permission_id) VALUES (1, 1), (1, 2), (2, 3)");
        }

        public function testAddUserToGroup(): void
        {
            $this->user->addUserToGroup(1, 1);
            $stmt = $this->db->prepare("SELECT * FROM user_groups WHERE user_id = ? AND group_id = ?");
            $stmt->execute([1, 1]);
            $this->assertTrue($stmt->fetch() !== false);
        }

        public function testRemoveUserFromGroup(): void
        {
            $this->user->addUserToGroup(1, 1);
            $this->user->removeUserFromGroup(1, 1);
            $stmt = $this->db->prepare("SELECT * FROM user_groups WHERE user_id = ? AND group_id = ?");
            $stmt->execute([1, 1]);
            $this->assertFalse($stmt->fetch());
        }

        public function testGetUserPermissions(): void
        {
            $this->user->addUserToGroup(1, 1);
            $permissions = $this->user->getUserPermissions(1);
            $this->assertEquals(['send_messages' => 'yes', 'service_api' => 'yes', 'debug' => 'no'], $permissions);
        }


        public function testAddUserToBlocked(): void
        {
            $this->user->addUserToBlocked(1, 3);
            $stmt = $this->db->prepare("SELECT * FROM blocked_users WHERE user_id = ? AND permission_id = ?");
            $stmt->execute([1, 3]);
            $this->assertTrue($stmt->fetch() !== false);
        }

        public function testRemoveUserFromBlocked(): void
        {
            $this->user->addUserToBlocked(1, 3);
            $this->user->removeUserFromBlocked(1, 3);
            $stmt = $this->db->prepare("SELECT * FROM blocked_users WHERE user_id = ? AND permission_id = ?");
            $stmt->execute([1, 3]);
            $this->assertFalse($stmt->fetch());
        }
    }
?>