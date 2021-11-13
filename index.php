<?php
include "classes/task.php";

// add a task 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!is_null($_POST['taskName']) and is_string($_POST['taskName'])) {
        $taskObj = new task;
        $taskObj->addTask($_POST['taskName']);
        header("location: {$_SERVER['PHP_SELF']}?task=added");
    }
}

// display list of tasks
$allTasks = new task;
$allTasks = $allTasks->displayTask();

include "views/tpl-index.php";
