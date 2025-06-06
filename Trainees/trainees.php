<?php
require 'db.php';

// Handle add trainee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_trainee'])) {
    $first_names = trim($_POST['first_names']);
    $last_name = trim($_POST['last_name']);
    $gender = $_POST['gender'] ?? '';
    $trade_id = intval($_POST['trade_id']);

    if ($first_names && $last_name && in_array($gender, ['Male', 'Female']) && $trade_id) {
        $stmt = $conn->prepare("INSERT INTO Trainees (FirstNames, LastName, Gender, Trade_Id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $first_names, $last_name, $gender, $trade_id);
        $stmt->execute();
        header("Location: trainees.php");
        exit;
    } else {
        $error = "All fields are required and must be valid.";
    }
}

// Fetch trainees and trades
$result = $conn->query("SELECT tr.Trainee_Id, tr.FirstNames, tr.LastName, tr.Gender, td.Trade_Name
                        FROM Trainees tr
                        LEFT JOIN Trades td ON tr.Trade_Id = td.Trade_Id
                        ORDER BY tr.LastName, tr.FirstNames");

$trades = $conn->query("SELECT Trade_Id, Trade_Name FROM Trades ORDER BY Trade_Name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainees Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Trainees Management</h2>

        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-600 bg-red-100 p-3 rounded text-center font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
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
        <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <input type="text" name="first_names" placeholder="First Names" required
                   class="col-span-1 md:col-span-1 px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
            <input type="text" name="last_name" placeholder="Last Name" required
                   class="col-span-1 md:col-span-1 px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
            <select name="gender" required
                    class="col-span-1 md:col-span-1 px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                <option value="">Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <select name="trade_id" required
                    class="col-span-1 md:col-span-1 px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                <option value="">Select Trade</option>
                <?php while ($trade = $trades->fetch_assoc()): ?>
                    <option value="<?= $trade['Trade_Id'] ?>"><?= htmlspecialchars($trade['Trade_Name']) ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="add_trainee"
                    class="col-span-1 md:col-span-1 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition px-4 py-2">
                Add
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100 text-gray-700 font-semibold">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">First Names</th>
                        <th class="border px-4 py-2">Last Name</th>
                        <th class="border px-4 py-2">Gender</th>
                        <th class="border px-4 py-2">Trade</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2"><?= $row['Trainee_Id'] ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['FirstNames']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['LastName']) ?></td>
                            <td class="border px-4 py-2"><?= $row['Gender'] ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['Trade_Name']) ?></td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="trainee_edit.php?id=<?= $row['Trainee_Id'] ?>"
                                   class="inline-block px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs font-medium">Edit</a>
                                <a href="trainee_delete.php?id=<?= $row['Trainee_Id'] ?>"
                                   onclick="return confirm('Delete this trainee?')"
                                   class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-medium">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
