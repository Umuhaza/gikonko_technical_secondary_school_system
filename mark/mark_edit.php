<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM Marks WHERE Mark_Id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mark = $stmt->get_result()->fetch_assoc();

if (!$mark) {
    header("Location: marks.php");
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_mark'])) {
    $formative = intval($_POST['formative']);
    $summative = intval($_POST['summative']);
    $total = $formative + $summative;

    if ($formative >= 0 && $summative >= 0 && $formative <= 50 && $summative <= 50) {
        $stmt = $conn->prepare("UPDATE Marks SET Formative_Assessment = ?, Summative_Assessment = ?, Total_Marks = ? WHERE Mark_Id = ?");
        $stmt->bind_param("iiii", $formative, $summative, $total, $id);
        $stmt->execute();
        header("Location: marks.php");
        exit;
    } else {
        $error = "Scores must be between 0 and 50.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Mark</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
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
    <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Edit Mark</h2>

        <?php if (isset($error)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Formative (/50)</label>
                <input type="number" name="formative" required
                       class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"
                       value="<?= $mark['Formative_Assessment'] ?>">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Summative (/50)</label>
                <input type="number" name="summative" required
                       class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"
                       value="<?= $mark['Summative_Assessment'] ?>">
            </div>

            <div class="flex justify-between items-center">
                <button name="update_mark"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md">
                    Update Mark
                </button>
                <a href="marks.php"
                   class="text-gray-600 hover:text-gray-800 px-4 py-2 border border-gray-300 rounded-md">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>
