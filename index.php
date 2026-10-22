<?php 
session_start();

// Default values for initial page load
$isbn           = "";
$title          = "";
$pages          = "";
$year_published = "";
$status         = "Borrowed";
$date_returned  = "";
$days_borrowed  = 0;
$daily_rate     = 1.50;
$penalty_price  = 0.00;
$borrow_fee     = 0.00;
$total_payment  = 0.00;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>St. Ignatius College - Book Borrowing System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h2>St. Ignatius College Library</h2>
        <p>Book Borrowing System</p>
    </div>

    <form action="process.php" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($isbn); ?>" required>
            </div>

            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>

            <div class="form-group">
                <label for="pages">Number of Pages</label>
                <input type="number" id="pages" name="pages" min="1" value="<?php echo htmlspecialchars($pages); ?>" required>
            </div>

            <div class="form-group">
                <label for="year_published">Year Published</label>
                <input type="number" id="year_published" name="year_published" value="<?php echo htmlspecialchars($year_published); ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Book Status</label>
                <select id="status" name="status" required>
                    <option value="Available" <?php if ($status === 'Available') echo 'selected'; ?>>Available</option>
                    <option value="Borrowed"  <?php if ($status === 'Borrowed') echo 'selected'; ?>>Borrowed</option>
                    <option value="Lost"      <?php if ($status === 'Lost') echo 'selected'; ?>>Lost</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date_returned">Date Returned</label>
                <input type="date" id="date_returned" name="date_returned" value="<?php echo htmlspecialchars($date_returned); ?>" required>
            </div>

            <div class="form-group">
                <label for="days_borrowed">Days Borrowed</label>
                <input type="number" id="days_borrowed" name="days_borrowed" min="0" value="<?php echo htmlspecialchars($days_borrowed); ?>" required>
            </div>

            <div class="form-group">
                <label for="daily_rate">Daily Rate ($)</label>
                <input type="number" id="daily_rate" name="daily_rate" step="0.01" min="0" value="<?php echo number_format((float)$daily_rate, 2); ?>" required>
            </div>

            <div class="form-group full-width">
                <label for="penalty_price">Penalty Price ($)</label>
                <input type="number" id="penalty_price" name="penalty_price" step="0.01" min="0" value="<?php echo number_format((float)$penalty_price, 2); ?>">
            </div>
        </div>

        <button type="submit">Record & Calculate Total</button>
    </form>

    <?php if (isset($_SESSION['receipt'])) { 
        $receipt = $_SESSION['receipt']; 
    ?>
        <div class="receipt">
            <h3>Transaction Receipt</h3>
            <div class="receipt-row"><span>ISBN:</span> <strong><?php echo htmlspecialchars($receipt['isbn']); ?></strong></div>
            <div class="receipt-row"><span>Book Title:</span> <strong><?php echo htmlspecialchars($receipt['title']); ?></strong></div>
            <div class="receipt-row"><span>Pages:</span> <strong><?php echo htmlspecialchars($receipt['pages']); ?></strong></div>
            <div class="receipt-row"><span>Year Published:</span> <strong><?php echo htmlspecialchars($receipt['year_published']); ?></strong></div>
            <div class="receipt-row"><span>Status:</span> <strong><?php echo htmlspecialchars($receipt['status']); ?></strong></div>
            <div class="receipt-row"><span>Date Returned:</span> <strong><?php echo htmlspecialchars($receipt['date_returned']); ?></strong></div>
            <hr>
            <div class="receipt-row">
                <span>Borrow Fee (<?php echo $receipt['days_borrowed']; ?> days @ $<?php echo number_format($receipt['daily_rate'], 2); ?>/day):</span> 
                <strong>$<?php echo number_format($receipt['borrow_fee'], 2); ?></strong>
            </div>
            <div class="receipt-row">
                <span>Penalty Price:</span> 
                <strong>$<?php echo number_format($receipt['penalty_price'], 2); ?></strong>
            </div>
            <div class="receipt-row total">
                <span>Total Payment:</span> 
                <span>$<?php echo number_format($receipt['total_payment'], 2); ?></span>
            </div>
        </div>
    <?php 
        unset($_SESSION['receipt']);
    } 
    ?>
</div>

</body>
</html>