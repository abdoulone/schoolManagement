<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/eventList.php -->
<?php
require '../../config.php';

$stmt = $pdo->prepare("SELECT * FROM events WHERE is_active = 1 ORDER BY date, time");
$stmt->execute();
$events = $stmt->fetchAll();
?>
<?php foreach ($events as $event): ?>
  <div class="p-5 rounded-md border-2 border-gray-100 border-t-4 odd:border-t-secondary even:border-t-primary" key="<?= $event['id'] ?>">
    <div class="flex items-center justify-between">
      <h1 class="font-semibold text-gray-600"><?= htmlspecialchars($event['title']) ?></h1>
      <span class="text-gray-300 text-xs">
        <?= date("H:i", strtotime($event['time'])) ?>
      </span>
    </div>
    <p class="mt-2 text-gray-400 text-sm"><?= htmlspecialchars($event['content']) ?></p>
  </div>
<?php endforeach; ?>