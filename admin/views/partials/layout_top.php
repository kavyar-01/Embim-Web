<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EMBIM Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style/style.css" />
</head>
<body class="bg-gray-100 antialiased">
<div class="h-screen w-full flex flex-col overflow-hidden">
  <?php require_once __DIR__ . '/topbar.php'; ?>
  <div class="flex flex-1 overflow-hidden">
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    <main class="flex-1 overflow-y-auto flex flex-col">
      <div class="flex-1 p-6 md:p-8">
        <div class="max-w-6xl mx-auto">
