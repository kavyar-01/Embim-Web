<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Check if column exists first
    $checkSql = "SHOW COLUMNS FROM `cars` LIKE 'stock'";
    $stmt = $conn->prepare($checkSql);
    $stmt->execute();
    $columnExists = $stmt->fetch();

    if ($columnExists) {
        $sql = "ALTER TABLE `cars` DROP COLUMN `stock`";
        $conn->exec($sql);
        echo "Column 'stock' successfully dropped from 'cars' table.\n";
    } else {
        echo "Column 'stock' does not exist in 'cars' table. Migration skipped.\n";
    }

} catch (PDOException $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
}
?>
