<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}
include "connection.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            color: #333;
        }
        .navbar {
            background-color: #212121; 
        }
        .navbar-brand {
            color: #ffffff;
            font-weight: bold;
        }
        .navbar-brand:hover {
            color: #e0e0e0;
        }
        .btn-primary {
            background-color: #424242; 
            border: none;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #616161; 
        }
        table {
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }
        thead {
            background-color: #212121; 
            color: #ffffff;
        }
        th, td {
            text-align: center;
        }
        .btn-success {
            background-color: #4caf50; 
            border: none;
            color: #ffffff;
        }
        .btn-success:hover {
            background-color: #388e3c; 
        }
        .btn-danger {
            background-color: #f44336; 
            border: none;
            color: #ffffff;
        }
        .btn-danger:hover {
            background-color: #c62828; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">PHP CRUD Operation</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary nav-link text-white" href="create.php">Add New</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>EMAIL</th>
                    <th>PHONE</th>
                    <th>JOINING DATE</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "connection.php";
                $sql = "SELECT * FROM crud2 WHERE is_deleted = 0 ORDER BY join_date DESC";
                $result = $conn->query($sql);
                if (!$result) {
                    die("Invalid query!");
                }
                $countIndex = 0;
                while ($row = $result->fetch_assoc()) {
                    $countIndex++;
                    echo "
                    <tr>
                        <th>{$countIndex}</th>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['join_date']}</td>
                        <td>
                            <a class='btn btn-success' href='edit.php?id={$row['id']}'>Edit</a>
                            <a class='btn btn-danger' href='delete.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>


