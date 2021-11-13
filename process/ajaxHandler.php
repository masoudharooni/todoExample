<?php
include "../classes/task.php";
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) or strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die("acsess denied!");
}


if ($_POST['action'] == "deleteTask") {
    $taskObj = new task;
    echo $taskObj->deleteTask($_POST['id']);
}

if ($_POST['action'] == "editData") {
    $taskObj = new task;
    echo ($taskObj->displayTask($_POST['id'])[0]->name);
}

if ($_POST['action'] == "editTask") {
    $taskObj = new task;
    echo ($taskObj->updateTask($_POST['id'], $_POST['name']));
}

if ($_POST['action'] == "doneTask") {
    $taskObj = new task;
    echo ($taskObj->doneTask($_POST['id']));
}
