<?php
require 'admin/config/database.php';
$pdo = getPDO();
$stmt = $pdo->query('DESCRIBE users');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
