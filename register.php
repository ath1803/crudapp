<?php
include "connection.php";

$error = "";
$email_exists = "";
$password_mismatch = "";
$password_length_error = "";

$email = "";
$password = "";
$confirm_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $email_exists = "Email already exists!";
        }

        if ($password !== $confirm_password) {
            $password_mismatch = "Passwords do not match!";
        }

        if (strlen($password) < 8) {
            $password_length_error = "Password must be at least 8 characters!";
        }

        if (empty($email_exists) && empty($password_mismatch) && empty($password_length_error)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $q = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($q);
            $stmt->bind_param("ss", $email, $password_hash);
            $stmt->execute();
            header('location: login.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
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
            border: 1px solid #ced4da;
            transition: border-color 0.2s;
        }
        .form-control.is-invalid {
            border-color: #d93025; 
        }
        .error-message {
            color: #d93025; 
            font-size: 14px;
            margin-top: 5px;
            text-align: left;
        }
        .login-link {
            margin-top: 15px;
            display: block;
            color: #1a73e8;
            text-decoration: none;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
    <title>Register</title>
</head>
<body>
    <div class="form-container">
        <h2 class="mb-4">Register</h2>
        <form method="post" id="register-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control <?php echo (!empty($email_exists) || !empty($error)) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <?php if (!empty($email_exists)) { echo "<div class='error-message'>$email_exists</div>"; } ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control <?php echo (!empty($password_length_error) || !empty($error)) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                <?php if (!empty($password_length_error)) { echo "<div class='error-message'>$password_length_error</div>"; } ?>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control <?php echo (!empty($password_mismatch) || !empty($error)) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                <?php if (!empty($password_mismatch)) { echo "<div class='error-message'>$password_mismatch</div>"; } ?>
            </div>
            <?php if (!empty($error)) { echo "<div class='error-message'>$error</div>"; } ?>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <a href="login.php" class="login-link">Already have an account? Login here</a>
        </form>
    </div>

    <script>
        // Clear the error as user starts typing in email, password, and confirm password fields
        document.getElementById('email').addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorMsg = this.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message')) {
                errorMsg.remove();
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorMsg = this.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message')) {
                errorMsg.remove();
            }
        });

        document.getElementById('confirm_password').addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorMsg = this.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('error-message')) {
                errorMsg.remove();
            }
        });
    </script>
</body>
</html>


