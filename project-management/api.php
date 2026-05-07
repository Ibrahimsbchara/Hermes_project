<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';
$input  = (array) json_decode(file_get_contents('php://input'), true);

function jsonOut(mixed $data): void {
    echo json_encode($data);
    exit;
}

try {
    $db = getDb();

    switch ($action) {

        /* ---- READ ---- */
        case 'init':
            $tasks      = $db->query("SELECT * FROM tasks ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
            $categories = $db->query("SELECT name FROM categories ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
            $settings   = $db->query("SELECT * FROM sprint_settings WHERE id=1")->fetch(PDO::FETCH_ASSOC);
            jsonOut([
                'tasks'      => $tasks,
                'categories' => $categories,
                'capacity'   => (float) $settings['capacity'],
                'locked'     => (bool)  $settings['locked'],
            ]);

        /* ---- TASKS ---- */
        case 'create_task':
            $stmt = $db->prepare("INSERT INTO tasks (name, description, category, priority, effort)
                                  VALUES (:name, :desc, :cat, :pri, :eff)");
            $stmt->execute([
                ':name' => $input['name'],
                ':desc' => $input['desc'] ?? '',
                ':cat'  => $input['tag']  ?? 'General',
                ':pri'  => $input['priority'] ?? 'medium',
                ':eff'  => (float) ($input['effort'] ?? 0),
            ]);
            $id   = (int) $db->lastInsertId();
            $task = $db->query("SELECT * FROM tasks WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
            jsonOut(['success' => true, 'task' => $task]);

        case 'update_task':
            $id   = (int) ($input['id'] ?? 0);
            $stmt = $db->prepare("UPDATE tasks
                                  SET name=:name, description=:desc, category=:cat,
                                      priority=:pri, effort=:eff
                                  WHERE id=:id");
            $stmt->execute([
                ':name' => $input['name'],
                ':desc' => $input['desc'] ?? '',
                ':cat'  => $input['tag']  ?? 'General',
                ':pri'  => $input['priority'] ?? 'medium',
                ':eff'  => (float) ($input['effort'] ?? 0),
                ':id'   => $id,
            ]);
            $task = $db->query("SELECT * FROM tasks WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
            jsonOut(['success' => true, 'task' => $task]);

        case 'delete_task':
            $id   = (int) ($input['id'] ?? 0);
            $stmt = $db->prepare("DELETE FROM tasks WHERE id=?");
            $stmt->execute([$id]);
            jsonOut(['success' => true]);

        case 'toggle_accept':
            $id   = (int) ($input['id'] ?? 0);
            $task = $db->query("SELECT * FROM tasks WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
            if (!$task) { jsonOut(['success' => false, 'error' => 'Task not found.']); }

            if (!$task['accepted']) {
                $settings = $db->query("SELECT capacity FROM sprint_settings WHERE id=1")->fetch(PDO::FETCH_ASSOC);
                $used     = (float) $db->query("SELECT COALESCE(SUM(effort),0) FROM tasks WHERE accepted=1 AND id!=$id")->fetchColumn();
                if ($used + (float) $task['effort'] > (float) $settings['capacity']) {
                    jsonOut(['success' => false, 'error' => 'Not enough capacity.']);
                }
            }

            $newVal = $task['accepted'] ? 0 : 1;
            $db->prepare("UPDATE tasks SET accepted=? WHERE id=?")->execute([$newVal, $id]);
            jsonOut(['success' => true, 'accepted' => (bool) $newVal]);

        /* ---- SPRINT ---- */
        case 'lock_sprint':
            $db->exec("UPDATE sprint_settings SET locked=1 WHERE id=1");
            jsonOut(['success' => true]);

        case 'unlock_sprint':
            $db->exec("UPDATE sprint_settings SET locked=0 WHERE id=1");
            jsonOut(['success' => true]);

        case 'clear_sprint':
            $db->exec("UPDATE tasks SET accepted=0");
            $db->exec("UPDATE sprint_settings SET locked=0 WHERE id=1");
            jsonOut(['success' => true]);

        case 'update_capacity':
            $val  = (float) ($input['capacity'] ?? 0);
            $stmt = $db->prepare("UPDATE sprint_settings SET capacity=? WHERE id=1");
            $stmt->execute([$val]);
            jsonOut(['success' => true]);

        /* ---- CATEGORIES ---- */
        case 'add_category':
            $stmt = $db->prepare("INSERT OR IGNORE INTO categories (name) VALUES (?)");
            $stmt->execute([$input['name'] ?? '']);
            $categories = $db->query("SELECT name FROM categories ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
            jsonOut(['success' => true, 'categories' => $categories]);

        case 'remove_category':
            $stmt = $db->prepare("DELETE FROM categories WHERE name=?");
            $stmt->execute([$input['name'] ?? '']);
            $categories = $db->query("SELECT name FROM categories ORDER BY id")->fetchAll(PDO::FETCH_COLUMN);
            jsonOut(['success' => true, 'categories' => $categories]);

        default:
            http_response_code(400);
            jsonOut(['error' => 'Unknown action']);
    }

} catch (Throwable $e) {
    http_response_code(500);
    jsonOut(['error' => $e->getMessage()]);
}
