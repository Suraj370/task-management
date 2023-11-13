<?php 
session_start();

// Redirect to login if user is not logged in
if(empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include("db.php");

if(isset($_POST['task']) && $_POST['task'] !== '') {
    $user = $_SESSION['user'];
    $task = mysqli_real_escape_string($connection, $_POST['task']);
    
    // Get the maximum index to determine the new task's index
    $maxIndexQuery = "SELECT MAX(i) AS maxIndex FROM tasks";
    $maxIndexResult = mysqli_query($connection, $maxIndexQuery);
    $maxIndexRow = mysqli_fetch_assoc($maxIndexResult);
    $index = ($maxIndexRow['maxIndex'] !== null) ? $maxIndexRow['maxIndex'] + 1 : 1;

    // Insert the new task
    $insertTaskQuery = "INSERT INTO tasks (i, task, done, username) VALUES ('$index', '$task', '0', '$user')";
    $insertTaskResult = mysqli_query($connection, $insertTaskQuery);

    if (!$insertTaskResult) {
        die("Error: " . $insertTaskResult . "<br>" . mysqli_error($connection));
    }

    header("Location: index.php");
    exit();
}

// Handle task deletion
if(isset($_GET['index'])) {
    $indexToDelete = mysqli_real_escape_string($connection, $_GET['index']);
    $deleteQuery = "UPDATE tasks SET done = 1 WHERE i = '$indexToDelete'";
    $deleteResult = mysqli_query($connection, $deleteQuery);

    if (!$deleteResult) {
        die("Error: " . $deleteResult . "<br>" . mysqli_error($connection));
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TASK MANAGER</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
     <div class="container">
        <button id="logout">LOGOUT</button><br>
        <h4><?php echo "WELCOME ", $_SESSION['user'], "!" ?></h4>
        <h1>TASK LIST</h1>
        <form method="post" action="index.php">
            <input type="text" name="task" placeholder="Add another task..." autocomplete="off">
            <input type="submit" name="go" value="Add">
        </form>
        <h4>Double-click on a task to delete</h4>
        <ul style="list-style: none;">
        <?php
            $user = $_SESSION['user'];
            $query = "SELECT * FROM tasks WHERE done = 0 AND username = '$user'";
            $printMessages = mysqli_query($connection, $query);

            if($printMessages) {
                while($row = mysqli_fetch_array($printMessages)) {
                    echo "<li id='$row[i]'>".$row['task']."</li>";
                }
            } else {
                die("Error: " . mysqli_error($connection));
            }
        ?>
        </ul>
    </div> 
</body>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("li").on('click',function(){
        $(this).fadeOut();
        window.location = "?index=" + $(this).attr("id");
    });

    $("#logout").on('click', function(){
        location.href = "logout.php";
    })
  });
</script>
</html>
