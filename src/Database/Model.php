<?php

namespace App\Database;

class Model
{
    protected static $table;

    protected static function db()
    {
        return Connection::getInstance();
    }

    public static function all()
    {
        $stmt = static::db()->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $stmt = static::db()->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = static::db()->prepare(
            "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})"
        );
        $stmt->execute(array_values($data));

        return static::db()->lastInsertId();
    }

    public static function update($id, $data)
    {
        $set = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));

        $stmt = static::db()->prepare(
            "UPDATE " . static::$table . " SET {$set} WHERE id = ?"
        );
        $stmt->execute([...array_values($data), $id]);

        return $stmt->rowCount();
    }

    public static function delete($id)
    {
        $stmt = static::db()->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }
}