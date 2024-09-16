<?php
include "connection.php";

$emailError = "";
$passwordError = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $emailError = "Email is required!";
    } else if (empty($password)) {
        $passwordError = "Password is required!";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $emailError = "This email does not exist!";
        } else if (!password_verify($password, $user['password'])) {
            $passwordError = "Invalid password!";
        } else {
            session_start();
            $_SESSION['user_id'] = $user['id'];
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
        .register-link {
            margin-top: 15px;
            display: block;
            color: #1a73e8;
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <h2 class="mb-4">Login</h2>
        <form method="post" id="login-form">
            <div class="form-group">
                <label for="email">Email-ID</label>
                <input type="email" class="form-control <?php echo !empty($emailError) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <?php if (!empty($emailError)) { echo "<div class='error-message'>$emailError</div>"; } ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control <?php echo !empty($passwordError) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                <?php if (!empty($passwordError)) { echo "<div class='error-message'>$passwordError</div>"; } ?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <a href="register.php" class="register-link">Don't have an account? Register here</a>
        </form>
    </div>

    <script>
        // Clear the error as user starts typing in email and password fields
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
    </script>
</body>
</html>


