<?php
require 'db.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
    header("Location: trades.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trade_name = trim($_POST['trade_name']);
    if ($trade_name != '') {
        $stmt = $conn->prepare("UPDATE Trades SET Trade_Name = ? WHERE Trade_Id = ?");
        $stmt->bind_param('si', $trade_name, $id);
        $stmt->execute();
        header("Location: trades.php");
        exit;
    } else {
        $error = "Trade name is required.";
    }
}

$stmt = $conn->prepare("SELECT Trade_Name FROM Trades WHERE Trade_Id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($trade_name);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Trade</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        
        <h2 class="text-2xl font-semibold mb-6 text-center">Edit Trade</h2>

        <?php if (isset($error)): ?>
            <div class="mb-4 text-red-600 font-medium text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="trade_name" class="block text-gray-700 font-medium mb-2">Trade Name</label>
                <input
                    id="trade_name"
                    type="text"
                    name="trade_name"
                    value="<?= htmlspecialchars($trade_name) ?>"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <div class="flex justify-between items-center">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition"
                >
                    Update
                </button>
                <a
                    href="trades.php"
                    class="text-gray-600 hover:text-gray-900 font-semibold"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>

</body>
</html>
