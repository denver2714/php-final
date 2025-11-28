<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        status TEXT DEFAULT 'active',
        is_completed INTEGER DEFAULT 0,
        position INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");


    try {
        $db->exec("ALTER TABLE tasks ADD COLUMN is_completed INTEGER DEFAULT 0");
    } catch (PDOException $e) {
       
    }

    try {
        $db->exec("ALTER TABLE tasks ADD COLUMN position INTEGER DEFAULT 0");
    } catch (PDOException $e) {
        
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
