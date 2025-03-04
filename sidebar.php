<?php
$role = "admin";
$home =  array(
  "icon" => "./images/home.png",
  "label" => "Home",
  "href" => "./",
  "visible" => array("admin", "teacher", "student", "parent"),
);
$teacher = array(
  "icon" => "./images/teacher.png",
  "label" => "Teachers",
  "href" => "./TeacherIndex.php",
  "visible" => array("admin", "teacher"),
);
$tudent = array(
  "icon" => "./images/student.png",
  "label" => "Students",
  "href" => "./StudentIndex.php",
  "visible" => array("admin", "teacher"),
);
$parent = array(
  "icon" => "./images/parent.png",
  "label" => "Parents",
  "href" => "./ParentIndex.php",
  "visible" => array("admin", "teacher"),
);
$ubject = array(
  "icon" => "./images/subject.png",
  "label" => "Subjects",
  "href" => "./SubjectIndex.php",
  "visible" => array("admin", "teacher"),
);
$class = array(
  "icon" => "./images/class.png",
  "label" => "Classes",
  "href" => "./ClassesIndex.php",
  "visible" => array("admin",),
);
$lesson = array(
  "icon" => "./images/lesson.png",
  "label" => "Lessons",
  "href" => "./Lessons.php",
  "visible" => array("admin", "teacher"),
);
$exam = array(
  "icon" => "./images/exam.png",
  "label" => "Exams",
  "href" => "./ExamIndex.php",
  "visible" => array("admin", "teacher", "student", "parent",)
);
$assignment = array(
  "icon" => "./images/assignment.png",
  "label" => "Assignments",
  "href" => "./components/assignments/assignments.php",
  "visible" => array("admin", "teacher", "student", "parent",)
);
$result = array(
  "icon" => "./images/result.png",
  "label" => "Results",
  "href" => "./results",
  "visible" => array("admin", "teacher", "student", "parent",)
);
$attendance = array(
  "icon" => "./images/attendance.png",
  "label" => "Attendance",
  "href" => "./attendance",
  "visible" => array("admin", "teacher", "student", "parent",)
);
$event = array(
  "icon" => "./images/calendar.png",
  "label" => "Events",
  "href" => "./events",
  "visible" => array("admin", "teacher", "student", "parent",)
);
$message = array(
  "icon" => "./images/message.png",
  "label" => "Messages",
  "href" => "./messages",
  "visible" => array("admin", "teacher", "student", "parent",)
);
$announcement = array(
  "icon" => "./images/announcement.png",
  "label" => "Announcements",
  "href" => "./announcements",
  "visible" => array("admin", "teacher", "student", "parent",)
);
$menuItems = array(
  $home,
  $teacher,
  $tudent,
  $parent,
  $ubject,
  $class,
  $lesson,
  $exam,
  $assignment,
  $result,
  $attendance,
  $event,
  $message,
  $announcement

);

?>
<div class="mt-4 text-sm">

  <div class="flex flex-col gap-2">
    <span class="hidden lg:block text-gray-400 font-light my-4">
      Menu
    </span>
    <?php foreach ($menuItems as $i) {
      if (in_array($role, $i["visible"])) { ?>

        <a
          href="<?php echo $i["href"]; ?>"
          class="flex items-center justify-center lg:justify-start gap-4 text-gray-500 py-2 md:px-2 rounded-md hover:bg-[rgb(168,81,138);] hover:text-white">
          <img src="<?php echo $i["icon"]; ?>" alt="" width="20" height="20" />
          <span class="hidden lg:block"><?php echo $i["label"];
                                      };
                                    }; ?></span>
        </a>
        <span class="hidden lg:block text-gray-400 font-light my-4">Others</span>
        <a
          href="#"
          class="flex items-center justify-center lg:justify-start gap-4 text-gray-500 py-2 md:px-2 rounded-md hover:bg-[rgb(168,81,138);] hover:text-white">
          <img src="./images/profile.png" alt="" width="20" height="20" />
          <span class="hidden lg:block">Profile</span>
        </a>
        <a
          href="#"
          class="flex items-center justify-center lg:justify-start gap-4 text-gray-500 py-2 md:px-2 rounded-md hover:bg-[rgb(168,81,138);] hover:text-white">
          <img src="./images/setting.png" alt="" width="20" height="20" />
          <span class="hidden lg:block">Settings</span>
        </a>
        <a
          href="#"
          class="flex items-center justify-center lg:justify-start gap-4 text-gray-500 py-2 md:px-2 rounded-md hover:bg-[rgb(168,81,138);] hover:text-white">
          <img src="./images/Logout.png" alt="" width="20" height="20" />
          <span class="hidden lg:block">Logout</span>
        </a>
        <span class="text-sm text-gray-600 gap 1">

  </div>

</div>