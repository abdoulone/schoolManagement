<div class="bg-white rounded-xl w-full h-full p-4">
  <!-- TITLE -->
  <div class="flex justify-between items-center">
    <h1 class="text-lg font-semibold">Students</h1>
    <img src="./images/moreDark.png" alt="" width="20" height="20" />
  </div>
  <!-- CHART -->
  <div class="relative w-full h-[75%]">
    <?php require './components/Chart.php'; ?>
  </div>
  <!-- BOTTOM -->
  <div class="flex justify-center gap-16">
    <div class="flex flex-col gap-1">
      <div class="w-5 h-5 bg-[#36A2EB] rounded-full"></div>
      <h1 class="font-bold">Males</h1>
      <h2 class="text-xs text-gray-300">1,351</h2>
    </div>
    <div class="flex flex-col gap-1">
      <div class="w-5 h-5 bg-[#FF6384] rounded-full"></div>
      <h1 class="font-bold">Females</h1>
      <h2 class="text-xs text-gray-300">1,351</h2>
    </div>
  </div>
</div>
