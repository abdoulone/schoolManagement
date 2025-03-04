<div class="bg-white p-4 rounded-md">
      <EventCalendar />
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold my-4">Events</h1>
        <img src="./images/moreDark.png" alt="moreDark" width="20" height="20" />
      </div>
      <div class="flex flex-col gap-4">
      <?php include'./components/EventCalendar.php'; ?>
        <?php require'./components/eventList.php'; ?>
        <?php require'./components/eventList.php'; ?>
      </div>
    </div>