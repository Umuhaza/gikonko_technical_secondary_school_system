<?php
require 'db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header("Location: trainees.php");
    exit;
}

// Fetch trainee
$stmt = $conn->prepare("SELECT Trainee_Id, FirstNames, LastName, Gender, Trade_Id FROM Trainees WHERE Trainee_Id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$trainee = $result->fetch_assoc();

if (!$trainee) {
    header("Location: trainees.php");
    exit;
}

// Fetch trades for dropdown
$trades = $conn->query("SELECT Trade_Id, Trade_Name FROM Trades ORDER BY Trade_Name");

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_trainee'])) {
    $first_names = trim($_POST['first_names']);
    $last_name = trim($_POST['last_name']);
    $gender = $_POST['gender'] ?? '';
    $trade_id = intval($_POST['trade_id']);

    if ($first_names && $last_name && in_array($gender, ['Male', 'Female']) && $trade_id) {
        $stmt = $conn->prepare("UPDATE Trainees SET FirstNames = ?, LastName = ?, Gender = ?, Trade_Id = ? WHERE Trainee_Id = ?");
        $stmt->bind_param("sssii", $first_names, $last_name, $gender, $trade_id, $id);
        $stmt->execute();
        header("Location: trainees.php");
        exit;
    } else {
        $error = "All fields are required and must be valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Trainee</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <a href="home.php" style="
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    transition: background-color 0.3s ease;
">Back</a>
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-8">
        <h2 class="text-3xl font-semibold mb-6 text-center text-gray-800">Edit Trainee</h2>

        <?php if (isset($error)): ?>
            <div class="mb-6 text-center text-red-600 font-medium bg-red-100 p-3 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="first_names" class="block mb-2 font-medium text-gray-700">First Names</label>
                <input
                    id="first_names"
                    type="text"
                    name="first_names"
                    required
                    value="<?= htmlspecialchars($trainee['FirstNames']) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div>
                <label for="last_name" class="block mb-2 font-medium text-gray-700">Last Name</label>
                <input
                    id="last_name"
                    type="text"
                    name="last_name"
                    required
                    value="<?= htmlspecialchars($trainee['LastName']) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div>
                <label for="gender" class="block mb-2 font-medium text-gray-700">Gender</label>
                <select
                    id="gender"
                    name="gender"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="" disabled <?= $trainee['Gender'] ? '' : 'selected' ?>>Select Gender</option>
                    <option value="Male" <?= $trainee['Gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $trainee['Gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>

            <div>
                <label for="trade_id" class="block mb-2 font-medium text-gray-700">Trade</label>
                <select
                    id="trade_id"
                    name="trade_id"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="" disabled <?= $trainee['Trade_Id'] ? '' : 'selected' ?>>Select Trade</option>
                    <?php while ($trade = $trades->fetch_assoc()): ?>
                        <option value="<?= $trade['Trade_Id'] ?>" <?= $trade['Trade_Id'] == $trainee['Trade_Id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($trade['Trade_Name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="flex justify-between items-center">
                <button
                    type="submit"
                    name="update_trainee"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-md transition"
                >
                    Update Trainee
                </button>
                <a
                    href="trainees.php"
                    class="text-gray-600 hover:text-gray-900 font-semibold"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>
