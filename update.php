<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'research_management');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$project_id = $_GET['id'] ?? null; // Get project_id from the query string
$message = "";

// Fetch existing project details
if ($project_id) {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE project_id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $project = $result->fetch_assoc();
    if (!$project) {
        die("Project not found.");
    }
    $stmt->close();
}

// Update project details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $lead_researcher = $_POST['lead_researcher'];
    $funding_amount = $_POST['funding_amount'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'] ?: null;

    $stmt = $conn->prepare("UPDATE projects SET title = ?, lead_researcher = ?, funding_amount = ?, status = ?, start_date = ?, end_date = ? WHERE project_id = ?");
    $stmt->bind_param("ssdsssi", $title, $lead_researcher, $funding_amount, $status, $start_date, $end_date, $project_id);

    if ($stmt->execute()) {
        $message = "Project updated successfully.";
        header("Location: read.php"); // Redirect to read.php after successful update
        exit;
    } else {
        $message = "Error updating project: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project</title>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 500px;
            width: 100%;
        }

        .form-container h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #007BFF;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        a {
            text-decoration: none;
        }
    </style>

</head>

<body>
    <h1>Update Research Project</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="title">Project Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" maxlength="100" required><br>

        <label for="lead_researcher">Lead Researcher:</label>
        <input type="text" id="lead_researcher" name="lead_researcher" value="<?php echo htmlspecialchars($project['lead_researcher']); ?>" maxlength="50" required><br>

        <label for="funding_amount">Funding Amount (USD):</label>
        <input type="number" id="funding_amount" name="funding_amount" value="<?php echo htmlspecialchars($project['funding_amount']); ?>" step="0.01" required><br>

        <label for="status">Project Status:</label>
        <select id="status" name="status" required>
            <option value="Ongoing" <?php if ($project['status'] == "Ongoing") echo "selected"; ?>>Ongoing</option>
            <option value="Completed" <?php if ($project['status'] == "Completed") echo "selected"; ?>>Completed</option>
            <option value="Paused" <?php if ($project['status'] == "Paused") echo "selected"; ?>>Paused</option>
            <option value="Cancelled" <?php if ($project['status'] == "Cancelled") echo "selected"; ?>>Cancelled</option>
        </select><br>

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($project['start_date']); ?>" required><br>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($project['end_date']); ?>"><br>

        <button type="submit">Update Project</button>
        <button>    <a href="read.php">Back to Project List</a></button>
    </form>
</body>

</html>
