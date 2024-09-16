<?php
include "connection.php";

$id = $_GET['id'] ?? '';
$email_exists = "";
$phone_exists = "";
$error = "";

// Fetch existing record for editing
if ($id) {
    $sql = "SELECT * FROM crud2 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("Record not found.");
    }
    $record = $result->fetch_assoc();
    $name = $record['name'];
    $email = $record['email'];
    $phone = $record['phone'];
} else {
    die("Invalid ID.");
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($name) || empty($email) || empty($phone)) {
        $error = "All fields are required!";
    } else {
        // Check if email already exists, including deleted records
        $sql = "SELECT * FROM crud2 WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $email_exists = "Email already exists!";
        }

        // Check if phone number already exists, including deleted records
        $sql = "SELECT * FROM crud2 WHERE phone = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $phone, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $phone_exists = "Phone number already exists!";
        }

        if (empty($email_exists) && empty($phone_exists)) {
            $sql = "UPDATE crud2 SET name = ?, email = ?, phone = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $phone, $id);
            $stmt->execute();
            header('location: index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>Edit Record</title>
    <style>
        body {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #ffffff;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-control {
            border-radius: 6px;
            height: 40px;
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .btn-custom {
            background-color: #1a73e8;
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .btn-custom:hover {
            background-color: #1669c1;
        }
        .btn-secondary {
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            border-radius: 6px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .invalid-feedback {
            color: #dc3545;
            display: block;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="mb-4">Edit Member</h2>
        <?php if (!empty($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control <?php echo !empty($email_exists) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <?php if (!empty($email_exists)) { echo "<div class='invalid-feedback'>$email_exists</div>"; } ?>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control <?php echo !empty($phone_exists) ? 'is-invalid' : ''; ?>" id="phone" name="phone" pattern="[0-9]{10}" title="Please enter a 10-digit phone number" value="<?php echo htmlspecialchars($phone); ?>" required>
                <?php if (!empty($phone_exists)) { echo "<div class='invalid-feedback'>$phone_exists</div>"; } ?>
            </div>
            <button type="submit" name="submit" class="btn btn-custom">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const phoneInput = document.getElementById('phone');

            // Remove error class and message on input
            emailInput.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const feedback = this.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.innerText = '';
                    }
                }
            });

            phoneInput.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const feedback = this.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.innerText = '';
                    }
                }
            });
        });
    </script>
</body>
</html>

