<?php
include "Connection.php";
class task extends Connection
{
    public function addTask(string $taskName)
    {
        $sql = "INSERT INTO tasks (name) VALUES (?)";
        $stmt = ($this->conn)->prepare($sql);
        $stmt->execute([$taskName]);
    }

    public function displayTask(int $id = null)
    {
        $conditoin = null;
        if (!is_null($id)) {
            $conditoin = "WHERE id = ?";
        }
        $sql = "SELECT * FROM tasks {$conditoin}";
        $stmt = $this->conn->prepare($sql);
        if (!is_null($id)) {
            $stmt->execute([$id]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ) ?? null;
    }

    public function deleteTask(int $id): bool
    {
        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]) ?? false;
    }

    public function updateTask(int $id, string $name): bool
    {
        $sql = "UPDATE tasks SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $id]) ?? false;
    }

    public function doneTask(int $id): bool
    {
        $sql = "UPDATE tasks SET status = 1 - status WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]) ?? false;
    }
}
