<?php
// MySQL connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "testdb";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete functionality
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // safe
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");  // refresh
    exit();
}

// Fetch data
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Day23: Fetch & Display Data</title>
    <style>
        table { border-collapse: collapse; width: 70%; margin: 20px auto; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
        .delete-btn { color: red; text-decoration: none; }
    </style>
    <script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            window.location = "index.php?delete_id=" + id;
        }
    }
    </script>
</head>
<body>
    <h2 style="text-align:center">Students Table</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>". $row['id'] ."</td>";
                echo "<td>". $row['name'] ."</td>";
                echo "<td>". $row['email'] ."</td>";
                echo "<td>". $row['course'] ."</td>";
                echo "<td><a href='#' class='delete-btn' onclick='confirmDelete(". $row['id'] .")'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>