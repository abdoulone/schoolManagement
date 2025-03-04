<?php




require 'config.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<div class="flex items-center justify-between p-4">
  <!-- SEARCH BAR -->
  <div class="hidden md:flex items-center gap-2 text-xs rounded-full ring-[1.5px] ring-gray-300 px-2">
    <img src="./images/search.png" alt="" width="14" height="14" />
    <input
      type="text"
      placeholder="Search..."
      class="w-[200px] p-2 bg-transparent outline-none" />
  </div>
  <!-- ICONS AND USER -->
  <div class="flex items-center gap-6 justify-end w-full">
    <div class="bg-white rounded-full w-7 h-7 flex items-center justify-center cursor-pointer">
      <img src="./images/message.png" alt="" width="20" height="20" />
    </div>
    <div class="bg-white rounded-full w-7 h-7 flex items-center justify-center cursor-pointer relative">
      <img src="./images/announcement.png" alt="" width="20" height="20" />
      <div class="absolute -top-3 -right-3 w-5 h-5 flex items-center justify-center bg-primary text-white rounded-full text-xs">
        1
      </div>
    </div>
    <div class="flex flex-col">
      <span class="text-xs leading-3 font-medium"><?= htmlspecialchars($user['name']) ?></span>
      <span class="text-[10px] text-gray-500 text-right">
        <?= htmlspecialchars(ucfirst($user['role'])) ?>
      </span>
    </div>
    <img src="<?= htmlspecialchars(ucfirst($user['photo'])) ?>" alt="" width="36" height="36" class="rounded-full" />
    <button class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Logout</button>
  </div>
</div>