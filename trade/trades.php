<?php
require 'db.php';

// Handle add trade
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_trade'])) {
    $trade_name = trim($_POST['trade_name']);
    if ($trade_name != '') {
        $stmt = $conn->prepare("INSERT INTO Trades (Trade_Name) VALUES (?)");
        $stmt->bind_param('s', $trade_name);
        $stmt->execute();
        header("Location: trades.php");
        exit;
    } else {
        $error = "Trade name is required.";
    }
}

// Fetch all trades
$result = $conn->query("SELECT * FROM Trades ORDER BY Trade_Name");
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Trades Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-tr from-indigo-50 via-white to-indigo-50 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl p-8">
        <h1 class="text-4xl font-extrabold text-indigo-700 mb-10 text-center drop-shadow-md">
            Trades Management
        </h1>

        <?php if (isset($error)): ?>
            <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 px-6 py-4 text-center font-semibold shadow-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="flex flex-col sm:flex-row items-center gap-4 mb-10">
            <input
                type="text"
                name="trade_name"
                placeholder="New Trade Name"
                required
                class="flex-grow rounded-lg border border-indigo-300 px-5 py-3 text-lg text-indigo-900 placeholder-indigo-400 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition shadow-md"
            />
            <button
                type="submit"
                name="add_trade"
                class="px-8 py-3 rounded-lg bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 active:bg-indigo-800 shadow-lg transition transform active:scale-95"
                aria-label="Add new trade"
            >
                Add Trade
            </button>
        </form>

        <div class="overflow-x-auto rounded-lg border border-indigo-200 shadow-inner">
            <table class="min-w-full table-auto border-collapse text-indigo-900">
                <thead class="bg-indigo-100 font-semibold uppercase tracking-wide text-sm select-none">
                    <tr>
                        <th class="border-b border-indigo-300 px-6 py-4 text-left">Trade ID</th>
                        <th class="border-b border-indigo-300 px-6 py-4 text-left">Trade Name</th>
                        <th class="border-b border-indigo-300 px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-200">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-indigo-50 transition cursor-pointer">
                        <td class="px-6 py-4 border-b border-indigo-200 whitespace-nowrap font-mono text-sm"><?= $row['Trade_Id'] ?></td>
                        <td class="px-6 py-4 border-b border-indigo-200 font-semibold text-lg"><?= htmlspecialchars($row['Trade_Name']) ?></td>
                        <td class="px-6 py-4 border-b border-indigo-200 space-x-3">
                            <a href="trade_edit.php?id=<?= $row['Trade_Id'] ?>"
                               class="inline-block px-4 py-2 bg-yellow-400 text-yellow-900 rounded-lg font-semibold shadow hover:bg-yellow-500 transition"
                               aria-label="Edit trade <?= htmlspecialchars($row['Trade_Name']) ?>"
                            >
                                Edit
                            </a>
                            <a href="trade_delete.php?id=<?= $row['Trade_Id'] ?>"
                               onclick="return confirm('Delete this trade?')"
                               class="inline-block px-4 py-2 bg-red-500 text-white rounded-lg font-semibold shadow hover:bg-red-600 transition"
                               aria-label="Delete trade <?= htmlspecialchars($row['Trade_Name']) ?>"
                            >
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($result->num_rows == 0): ?>
                        <tr>
                            <td colspan="3" class="text-center py-8 text-indigo-400 italic font-semibold">
                                No trades found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
