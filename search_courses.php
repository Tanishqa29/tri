<?php
include('../db.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = "SELECT DISTINCT course_name FROM courses WHERE course_name LIKE '%$search%' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<li class='list-group-item search-result' onclick='selectCourse(\"" . htmlspecialchars($row['course_name']) . "\")'>" . htmlspecialchars($row['course_name']) . "</li>";
}
?>
