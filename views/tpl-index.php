<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo Example</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- fontawesome cdn -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- google font cdn -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <!-- bootstrap cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
    <!-- jQuery cdn -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <div id="container">
        <div class="title">
            <h3>Todo Example</h3>
            <p>This is an Example for test me!</p>
        </div>

        <div class="all-task">
            <?php foreach ($allTasks as $task) : ?>
                <div class="task">
                    <p class="taskName"><?= $task->name ?></p>
                    <div class="icons">
                        <i data-id="<?= $task->id ?>" class="far fa-trash-alt delete icon clickable"></i>
                        <i data-id="<?= $task->id ?>" class="fas fa-edit edit icon clickable"></i>
                        <i data-id="<?= $task->id ?>" class="far <?php
                                                                    $taskObj = new task;
                                                                    $taskObj = $taskObj->displayTask($task->id)[0]->status;
                                                                    echo $taskObj ? "fa-check-square" : "fa-square";
                                                                    ?> done icon clickable"></i>
                    </div>
                </div>
            <?php
            endforeach;
            ?>

        </div>
        <div class="add-task">
            <form action="process/ajaxHandler.php" method="POST" id="addTaskForm">
                <input type="text" class="newTask" name="taskName" placeholder="New Task . . ." required>
                <input type="submit" class="btn btn-outline-danger addBtn" value="add">
            </form>
        </div>

        <!-- modal for edit task -->
        <div class="myModal">
            <div class="modal-container">
                <i class="fas fa-times closeModal clickable"></i>
                <input type="text" id="editInput" name="editTask" class="editTaskInput" placeholder="New name for this task . . ." value="" required>
                <input type="submit" class="btn btn-outline-primary editBtn" value="edit">
            </div>
        </div>


        <!-- script for edit and delete tasks -->
        <script>
            
            const renderData = () => {
                $("#addTaskForm").submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var editserialize = form.serialize();
                    editserialize = decodeURIComponent(editserialize.replace(/%2F/g, " "))
                    $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: {
                            action: "addTask",
                            data: editserialize
                        },
                        // type: 'json',
                        success: function(response) {
                            responseObj = JSON.parse(response);
                            document.querySelector('.all-task').innerHTML += `<div class="task">
                    <p class="taskName">${responseObj['name']}</p>
                    <div class="icons">
                        <i data-id="${responseObj['id']}" class="far fa-trash-alt delete icon clickable"></i>
                        <i data-id="${responseObj['id']}" class="fas fa-edit edit icon clickable"></i>
                        <i data-id="${responseObj['id']}" class="far <?php
                                                                        $taskObj = new task;
                                                                        $taskObj = $taskObj->displayTask($task->id)[0]->status;
                                                                        echo $taskObj ? "fa-check-square" : "fa-square";
                                                                        ?> done icon clickable"></i>
                    </div>
                </div>`;
                renderData();
                            $('input.newTask').val('');
                        }
                    });
                });
                // delete tasks 
                $("i.delete").click(function(e) {
                    var id = $(this).attr("data-id");
                    if (confirm("Are you sure?")) {
                        $.ajax({
                            type: "post",
                            url: "process/ajaxHandler.php",
                            data: {
                                action: "deleteTask",
                                id: id
                            },
                            success: function(response) {
                                if (response == true) {
                                    // location.reload();
                                    // e.target.parentNode.parentNode.remove();
                                    e.target.closest('.task').remove();
                                } else {
                                    alert(response);
                                }
                            }
                        });
                    }
                });


                // modal coltrol
                $('.closeModal').click(function(e) {
                    e.preventDefault();
                    $(".myModal").fadeOut(1000);
                });

                var editId;
                var editEventBtn;
                $("i.edit").click(function(e) {
                    editEventBtn = e;
                    $('.myModal').fadeIn(1000);
                    editId = $(this).attr("data-id");
                    $.ajax({
                        type: "post",
                        url: "process/ajaxHandler.php",
                        data: {
                            action: "editData",
                            id: editId
                        },
                        success: function(response) {
                            $(".editTaskInput").val(response);
                        }
                    });
                });

                // edit task
                $(".editBtn").click(function(e) {
                    var taskName = $(".editTaskInput").val();
                    $.ajax({
                        type: "post",
                        url: "process/ajaxHandler.php",
                        data: {
                            action: "editTask",
                            id: editId,
                            name: taskName
                        },
                        success: function(response) {
                            if (response == true) {
                                var editInputValue = document.getElementById('editInput').value;
                                editEventBtn.target.closest('.task').querySelector('.taskName').innerHTML = editInputValue;
                                $('.myModal').fadeOut(600);
                            } else {
                                alert(response);
                            }
                        }
                    });
                });
                // });

                // done and undone task
                $('.done').click(function(e) {
                    e.preventDefault();
                    var taskId = $(this).attr("data-id");
                    $.ajax({
                        type: "post",
                        url: "process/ajaxHandler.php",
                        data: {
                            action: "doneTask",
                            id: taskId
                        },
                        success: function(response) {
                            if (response == true) {
                                if (e.target.classList.contains('fa-check-square')) {
                                    e.target.classList.remove('fa-check-square');
                                    e.target.classList.add('fa-square');
                                } else {
                                    e.target.classList.add('fa-check-square');
                                    e.target.classList.remove('fa-square');
                                }
                            } else {
                                alert(response);
                            }
                        }
                    });
                });
            }
            $(document).ready(function() {
                // add task without refresh
                renderData();

            });


        </script>
</body>

</html>