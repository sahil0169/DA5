<?php
$conn = new mysqli('localhost', 'root', '', 'research_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM projects");

echo "<table border='1'>
<tr>
    <th>Project ID</th>
    <th>Title</th>
    <th>Lead Researcher</th>
    <th>Funding Amount</th>
    <th>Status</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Actions</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['project_id']}</td>
        <td>{$row['title']}</td>
        <td>{$row['lead_researcher']}</td>
        <td>{$row['funding_amount']}</td>
        <td>{$row['status']}</td>
        <td>{$row['start_date']}</td>
        <td>{$row['end_date']}</td>
        <td>
            <a href='update.php?id={$row['project_id']}'>Edit</a> |
            <a href='delete.php?id={$row['project_id']}'>Delete</a>
        </td>
    </tr>";
}
echo "</table>";

$conn->close();
