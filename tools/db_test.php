<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=jrbvbproject','root','');
    echo "DB_OK\n";
} catch (Exception $e) {
    echo "DB_ERROR: " . $e->getMessage() . "\n";
}
