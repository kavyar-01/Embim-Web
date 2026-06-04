<?php
require 'admin/models/DashboardModel.php';
$model = new DashboardModel();
$globalAll = $model->getBookings();

$stats = [
    'pending'   => 0,
    'confirmed' => 0,
    'ongoing'   => 0,
    'completed' => 0,
    'cancelled' => 0,
];
foreach ($globalAll as $b) {
    $s = $b['status'] ?? 'pending';
    if (isset($stats[$s])) {
        $stats[$s]++;
    }
}
var_dump($stats);
