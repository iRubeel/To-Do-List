<?php
session_start();

// Sanitize and validate input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
    // Sanitize the task input
    $task = sanitize_input($_POST['task']);
    // Add task to session
    $_SESSION['tasks'][] = $task;

    // Redirect to prevent form resubmission on refresh
    header("Location: index.php");
    exit();
}

if (isset($_GET['remove'])) {
    $index = sanitize_input($_GET['remove']);
    if (isset($_SESSION['tasks'][$index])) {
        unset($_SESSION['tasks'][$index]);
        // Re-index array to maintain consistent indexing
        $_SESSION['tasks'] = array_values($_SESSION['tasks']);
    }

    // Redirect to prevent form resubmission on refresh
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced To-Do List</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/9b0a4deeaf.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="container">
        <aside id="sidebar">
            <h1>To-Do List</h1>
            <p>This is a simple to-do list application. Users can add new tasks to the list, mark tasks as completed, and remove tasks they no longer need to do. The application is implemented using PHP and uses PHP sessions to store data temporarily.</p>
        </aside>
        <main id="mainContent">
            <section id="listContainer">
                <form id="taskForm" method="post" action="index.php">
                    <input type="text" name="task" placeholder="New Task" aria-label="New Task" required>
                    <button type="submit">Add Task</button>
                </form>
                <div id="tasksList">
                    <?php if (!empty($_SESSION['tasks'])): ?>
                        <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                            <div class="task">
                                <p class="taskContent"><?= htmlspecialchars($task) ?></p>
                                <a href="index.php?remove=<?= $index ?>" class="garbage" aria-label="Remove Task"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
