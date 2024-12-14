<?php
// Root directory (ensure it is within the allowed path)
$root_dir = realpath(__DIR__);

// Start a session for user authentication and history logging
session_start();

// Simple user authentication (just for demo purposes)
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: login.php'); // Redirect to login page
    exit;
}

// Current directory (defaults to root if not set)
$current_dir = isset($_GET['dir']) ? realpath($_GET['dir']) : $root_dir;
if (!$current_dir || !is_dir($current_dir)) {
    $current_dir = $root_dir; // Fall back to root if the directory is invalid
}

// Add actions for files and directories
function listDirectory($dir)
{
    $files = scandir($dir);
    $directories = [];
    $regular_files = [];

    // Separate folders and files into different arrays
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            if (is_dir($dir . '/' . $file)) {
                $directories[] = $file;
            } else {
                $regular_files[] = $file;
            }
        }
    }

    // Show folders first
    foreach ($directories as $directory) {
        echo '<tr>';
        echo '<td><a href="?dir=' . urlencode($dir . '/' . $directory) . '">' . (is_dir($dir . '/' . $directory) ? '📁' : '') . $directory . '</a></td>';
        echo '<td>' . formatSize($dir . '/' . $directory) . '</td>';
        echo '<td><a href="?dir=' . urlencode($dir) . '&create=folder&name=' . urlencode($directory) . '">Create Folder</a> | ';
        echo '<a href="?dir=' . urlencode($dir) . '&delete=' . urlencode($directory) . '">Delete</a></td>';
        echo '</tr>';
    }

    // Show files below
    foreach ($regular_files as $file) {
        echo '<tr>';
        echo '<td>' . (is_dir($dir . '/' . $file) ? '📁' : '📄') . $file . '</td>';
        echo '<td>' . formatSize($dir . '/' . $file) . ' bytes</td>';
        echo '<td><a href="?dir=' . urlencode($dir) . '&edit=' . urlencode($file) . '">Edit</a> | ';
        echo '<a href="?dir=' . urlencode($dir) . '&delete=' . urlencode($file) . '">Delete</a> | ';
        echo '<a href="?dir=' . urlencode($dir) . '&download=' . urlencode($file) . '">Download</a></td>';
        echo '</tr>';
    }
}

// Format file sizes to a readable format
function formatSize($file)
{
    $size = filesize($file);
    if ($size >= 1073741824) {
        return round($size / 1073741824, 2) . ' GB';
    } elseif ($size >= 1048576) {
        return round($size / 1048576, 2) . ' MB';
    } elseif ($size >= 1024) {
        return round($size / 1024, 2) . ' KB';
    } else {
        return $size . ' bytes';
    }
}

// Log actions (for auditing)
function logAction($action)
{
    $log_file = 'action_log.txt';
    $log_entry = date('Y-m-d H:i:s') . " - {$_SESSION['user']} - $action\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Delete a file or directory
if (isset($_GET['delete'])) {
    $file_to_delete = $current_dir . '/' . $_GET['delete'];
    if (is_file($file_to_delete)) {
        unlink($file_to_delete);
        logAction("Deleted file: " . $_GET['delete']);
    } elseif (is_dir($file_to_delete)) {
        rmdir($file_to_delete);
        logAction("Deleted directory: " . $_GET['delete']);
    }
    header("Location: ?dir=" . urlencode($_GET['dir']));
}

// Edit a file
if (isset($_POST['save_file'])) {
    $file_to_edit = $current_dir . '/' . $_POST['file_name'];
    $new_content = $_POST['file_content'];
    file_put_contents($file_to_edit, $new_content);
    logAction("Edited file: " . $_POST['file_name']);
    header("Location: ?dir=" . urlencode($_GET['dir']));
}

// Create a new folder
if (isset($_GET['create']) && $_GET['create'] === 'folder' && isset($_GET['name'])) {
    $new_folder_name = $current_dir . '/' . $_GET['name'];
    if (!file_exists($new_folder_name)) {
        mkdir($new_folder_name);
        logAction("Created folder: " . $_GET['name']);
    }
    header("Location: ?dir=" . urlencode($_GET['dir']));
}

// Upload files
if (isset($_POST['upload'])) {
    foreach ($_FILES["files"]["name"] as $key => $name) {
        $target_file = $current_dir . '/' . basename($name);
        move_uploaded_file($_FILES["files"]["tmp_name"][$key], $target_file);
        logAction("Uploaded file: " . $name);
    }
    header("Location: ?dir=" . urlencode($_GET['dir']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShellTon - File Manager</title>
    <style>
        body {
            background-color: #2e2e2e;
            color: #ddd;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #ff6347;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #444;
        }
        tr:nth-child(even) {
            background-color: #333;
        }
        a {
            color: #66c2ff;
            text-decoration: none;
        }
        a:hover {
            color: #ff6347;
        }
        .upload-form, .create-folder-form {
            margin: 20px 0;
        }
        input[type="file"] {
            background-color: #444;
            color: #fff;
            padding: 10px;
        }
        button {
            background-color: #ff6347;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Welcome to ShellTon - File Manager</h1>
    <p>Current Directory: <a href="?dir=<?php echo urlencode(dirname($current_dir)); ?>"><?php echo $current_dir; ?></a></p>

    <!-- File upload form -->
    <div class="upload-form">
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="files[]" multiple>
            <button type="submit" name="upload">Upload Files</button>
        </form>
    </div>

    <!-- Create folder form -->
    <div class="create-folder-form">
        <form method="get">
            <input type="text" name="name" placeholder="New folder name" required>
            <button type="submit" name="create" value="folder">Create Folder</button>
        </form>
    </div>

    <!-- File and directory list -->
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Size</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php listDirectory($current_dir); ?>
        </tbody>
    </table>
</body>
</html>
