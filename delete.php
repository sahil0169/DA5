<?php 
$conn = new mysqli('localhost', 'root', '', 
'research_management'); if ($conn->connect_error) { 
die("Connection failed: " . $conn->connect_error); 
} 
$project_id = $_GET['id']; 
$sql = "DELETE FROM projects WHERE project_id='$project_id'"; 
if ($conn->query($sql) === TRUE) { 
echo "Project deleted successfully"; 
} else { echo "Error deleting project: " . 
$conn->error; 
} 
$conn->close();?> 