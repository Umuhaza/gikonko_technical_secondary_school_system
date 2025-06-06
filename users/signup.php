<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT User_Id FROM Users WHERE Username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username is already taken.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Assuming default role is 'user'
            $role = 'user';

            $insert_stmt = $conn->prepare("INSERT INTO Users (Username, Password, Role) VALUES (?, ?, ?)");
            $insert_stmt->bind_param('sss', $username, $hashed_password, $role);

            if ($insert_stmt->execute()) {
                // Redirect to login page after successful registration
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Create Account</h2>

        <?php if (isset($error)): ?>
            <p class="mb-4 text-red-600 font-medium text-center"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div>
                <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <button
                type="submit"
                class="w-full bg-green-600 text-white font-semibold py-2 rounded-md hover:bg-green-700 transition"
            >
                Register
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="mb-2 text-gray-600">Already have an account?</p>
            <a href="login.php"
                class="inline-block px-6 py-2 border border-green-600 text-green-600 font-semibold rounded-md hover:bg-green-600 hover:text-white transition"
            >
                Login
            </a>
        </div>
    </div>
</body>
</html>
