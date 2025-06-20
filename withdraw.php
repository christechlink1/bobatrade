<?php
require_once "includes/auth.php";
require_once "includes/db.php"; // Include the database connection file
requireLogin();

// Ensure $db is properly initialized
if (!isset($db)) {
    die("Database connection not established.");
}

// Handle form submission
if (isset($_POST['confirm_submit'])) { // Changed to confirm_submit for confirmation modal
    $method = $_POST['method'];
    $coin = $_POST['coin'];
    $amount = floatval($_POST['amount']);
    $details = $_POST['details'];

    // Save withdrawal request to the database
    $stmt = $db->prepare("INSERT INTO withdrawals (user_id, method, coin, amount, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $method, $coin, $amount, $details]);

    // Trigger the success modal
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>";
}

// Fetch recent withdrawals from the database
$stmt = $db->prepare("SELECT coin, method, amount, details, created_at FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->execute([$userId]);
$withdrawals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw - Bobatrade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #fff; /* White background */
            color: #000; /* Black font */
            font-family: sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .form-control {
            margin-bottom: 15px;
        }

        table {
            background-color: #fff; /* White background for table */
            color: #000; /* Black font */
        }

        th, td {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Withdraw Funds</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#withdrawModal">Withdraw</button>

        <!-- Withdraw Modal -->
        <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="withdrawModalLabel">Withdraw Funds</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="withdrawForm">
                        <div class="modal-body">
                            <label for="method">Payment Method</label>
                            <select class="form-control" name="method" id="method" required>
                                <option value="Bank Payment">Bank Payment</option>
                                <option value="Crypto Transfer">Crypto Transfer</option>
                                <option value="Mobile Money">Mobile Money</option>
                            </select>

                            <label for="coin">Select Coin</label>
                            <select class="form-control" name="coin" id="coin" required>
                                <option value="BTC">BTC</option>
                                <option value="ETH">ETH</option>
                                <option value="USDT">USDT</option>
                                <option value="BUSD">BUSD</option>
                                <option value="XRP">XRP</option>
                                <option value="DOGE">DOGE</option>
                                <option value="LTC">LTC</option>
                                <option value="SOL">SOL</option>
                                <option value="ADA">ADA</option>
                                <option value="DOT">DOT</option>
                                <option value="GHS">GHS</option>
                                <option value="NAIRA">NAIRA</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>

                            <label for="amount">Withdraw Amount</label>
                            <input type="number" step="0.0001" class="form-control" name="amount" id="amount" placeholder="Enter amount" required>

                            <label for="details">Payment Details</label>
                            <textarea class="form-control" name="details" id="details" placeholder="Enter payment details (e.g., bank account, wallet address)" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Withdrawal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <p>Are you sure you want to submit this withdrawal request?</p>
                            <input type="hidden" name="method" id="confirmMethod">
                            <input type="hidden" name="coin" id="confirmCoin">
                            <input type="hidden" name="amount" id="confirmAmount">
                            <input type="hidden" name="details" id="confirmDetails">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="confirm_submit" class="btn btn-primary">Confirm</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Withdrawal Submitted</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Withdrawals take 48 hours to be confirmed and dispersed. Thank you!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Withdrawals Section -->
        <div class="mt-5">
            <h1 class="text-center mb-4">Recent Withdrawals</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Coin</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Details</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdrawals as $index => $withdrawal): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($withdrawal['coin']) ?></td>
                            <td><?= htmlspecialchars($withdrawal['method']) ?></td>
                            <td><?= htmlspecialchars($withdrawal['amount']) ?></td>
                            <td><?= htmlspecialchars($withdrawal['details']) ?></td>
                            <td><?= htmlspecialchars($withdrawal['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.querySelector('.btn-primary[data-bs-target="#confirmModal"]').addEventListener('click', function () {
            document.getElementById('confirmMethod').value = document.getElementById('method').value;
            document.getElementById('confirmCoin').value = document.getElementById('coin').value;
            document.getElementById('confirmAmount').value = document.getElementById('amount').value;
            document.getElementById('confirmDetails').value = document.getElementById('details').value;
        });
    </script>
</body>
</html>