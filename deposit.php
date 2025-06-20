<?php
require_once "includes/auth.php";
require_once "includes/db.php"; // Include the database connection file
requireLogin();

// Ensure $db is properly initialized
if (!isset($db)) {
    die("Database connection not established.");
}

// Set SQLite PRAGMA commands
$db->exec("PRAGMA busy_timeout = 3000"); // Wait for 3 seconds if the database is locked
$db->exec("PRAGMA journal_mode = WAL"); // Use Write-Ahead Logging mode

// Retrieve the logged-in user's ID
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}
$userId = $_SESSION['user_id']; // Assuming user ID is stored in the session

// Handle form submission
if (isset($_POST['confirm_submit'])) {
    // Debugging: Log the entire $_POST and $_FILES arrays
    error_log(print_r($_POST, true));
    error_log(print_r($_FILES, true));

    // Validate form inputs
    $method = $_POST['method'] ?? '';
    $coin = $_POST['coin'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);
    $details = $_POST['details'] ?? '';
    $receiptPath = '';

    // Check for missing fields
    if (empty($method) || empty($coin) || $amount <= 0 || empty($details)) {
        die("All fields are required and must be valid.");
    }

    // Handle file upload
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/receipts/';
        $uploadFile = $uploadDir . basename($_FILES['receipt']['name']);

        // Create the uploads directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the server
        if (move_uploaded_file($_FILES['receipt']['tmp_name'], $uploadFile)) {
            $receiptPath = $uploadFile;
        } else {
            die("Failed to upload receipt.");
        }
    }

    // Debugging: Log values being inserted
    error_log("User ID: $userId");
    error_log("Method: $method");
    error_log("Coin: $coin");
    error_log("Amount: $amount");
    error_log("Details: $details");
    error_log("Receipt Path: $receiptPath");

    // Save deposit request to the database for admin approval
    try {
        $stmt = $db->prepare("INSERT INTO deposits (user_id, method, coin, amount, details, receipt, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $method, $coin, $amount, $details, $receiptPath, 'pending']);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

    // Trigger the success modal
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>";
}

// Fetch recent transactions from the database
$stmt = $db->prepare("SELECT coin, method, amount, details, receipt, created_at, status FROM deposits WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the database connection
$db = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit - Bobatrade</title>
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
        <h1>Deposit Funds</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#depositModal">Deposit</button>

        <!-- Deposit Modal -->
        <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="depositModalLabel">Deposit Funds</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="depositForm">
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
                            </select>

                            <label for="amount">Deposit Amount</label>
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
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Deposit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <p>Are you sure you want to submit this deposit request?</p>
                            <input type="hidden" name="method" id="confirmMethod">
                            <input type="hidden" name="coin" id="confirmCoin">
                            <input type="hidden" name="amount" id="confirmAmount">
                            <input type="hidden" name="details" id="confirmDetails">

                            <!-- File Upload Field -->
                            <label for="receipt">Upload Payment Receipt</label>
                            <input type="file" class="form-control" name="receipt" id="receipt" accept="image/*" required>
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
                        <h5 class="modal-title" id="successModalLabel">Deposit Submitted</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Deposit takes 48 hours to be confirmed and dispersed. Thank you!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Countdown Modal -->
        <div class="modal fade" id="countdownModal" tabindex="-1" aria-labelledby="countdownModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="countdownModalLabel">Transaction Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Transaction will be confirmed in:</p>
                        <p id="countdownTimer">20:00</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirmTransactionButton">Confirm Transaction</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Section -->
        <div class="mt-5">
            <h1 class="text-center mb-4">Recent Transactions</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Coin</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $index => $transaction): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($transaction['coin']) ?></td>
                            <td><?= htmlspecialchars($transaction['method']) ?></td>
                            <td><?= htmlspecialchars($transaction['amount']) ?></td>
                            <td><?= htmlspecialchars($transaction['details']) ?></td>
                            <td><?= htmlspecialchars($transaction['status']) ?></td>
                            <td><?= htmlspecialchars($transaction['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.querySelector('.btn-primary[data-bs-target="#confirmModal"]').addEventListener('click', function () {
        const method = document.getElementById('method').value;
        const coin = document.getElementById('coin').value;
        const amount = document.getElementById('amount').value;
        const detailsField = document.getElementById('details').value;

        // Update hidden fields for confirmation modal
        document.getElementById('confirmMethod').value = method;
        document.getElementById('confirmCoin').value = coin;
        document.getElementById('confirmAmount').value = amount;
        document.getElementById('confirmDetails').value = detailsField;

        // Debugging: Log values being set to hidden fields
        console.log("Method:", method);
        console.log("Coin:", coin);
        console.log("Amount:", amount);
       

        // Dynamically update modal content based on payment method
        const modalBody = document.querySelector('#confirmModal .modal-body');
        if (method === 'Mobile Money') {
            modalBody.innerHTML = `
                <p>Are you sure you want to submit this deposit request?</p>
                <p><strong>Mobile Money Details:</strong></p>
                <p>Network: Telecel</p>
                <p>Account: 0200949589</p>
                <p>Account Name: Boabatrade Company</p>
                <p>Make sure you click confirm when you finish the transaction.</p>
                <p>Having a problem?: WhatsApp:0200949589</p>
            `;
        } else {
            modalBody.innerHTML = `
                <p>Are you sure you want to submit this deposit request?</p>
                <p><strong>Details:</strong> ${detailsField}</p>
            `;
        }
    });

    // Countdown Timer Logic
    let countdownTimer = document.getElementById('countdownTimer');
    let countdownTime = 20 * 60; // 20 minutes in seconds

    function startCountdown() {
        const interval = setInterval(() => {
            const minutes = Math.floor(countdownTime / 60);
            const seconds = countdownTime % 60;
            countdownTimer.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            countdownTime--;

            if (countdownTime < 0) {
                clearInterval(interval);
                document.getElementById('confirmTransactionButton').disabled = false;
            }
        }, 1000);
    }

    document.getElementById('confirmTransactionButton').addEventListener('click', function () {
        alert('Transaction confirmed!');
    });

    document.querySelector('.btn-primary[data-bs-target="#countdownModal"]').addEventListener('click', startCountdown);
</script>
</body>
</html>

