<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 90%;
            width: 400px;
            box-sizing: border-box;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 1.5em;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
            font-size: 0.9em;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            box-sizing: border-box;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }

            .form-container h2 {
                font-size: 1.2em;
            }

            .form-group input,
            .form-group button {
                font-size: 0.9em;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 10px;
            }

            .form-container h2 {
                font-size: 1em;
            }

            .form-group input,
            .form-group button {
                font-size: 0.8em;
                padding: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>

</html>

<?php
// login.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '5751', 'manhole_db'); // Update with your database name

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check if the user is an admin
    $adminQuery = "SELECT * FROM admin WHERE admin_email = ? AND admin_password = ?";
    $stmt = $conn->prepare($adminQuery);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $adminResult = $stmt->get_result();

    if ($adminResult->num_rows > 0) {
        // Admin login successful
        header('Location: main.html');
        exit();
    }

    // Check if the user is a regular user
    $userQuery = "SELECT * FROM user WHERE user_email = ? AND user_password = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        // User login successful
        header('Location: main.html');
        exit();
    } else {
        // Login failed
        echo "<script>alert('Invalid email or password. Please try again.'); window.location.href = 'login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
