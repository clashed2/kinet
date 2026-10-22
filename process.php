<?php
session_start();
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isbn           = trim($_POST['isbn'] ?? '');
    $title          = trim($_POST['title'] ?? '');
    $pages          = (int)($_POST['pages'] ?? 0);
    $year_published = (int)($_POST['year_published'] ?? 0);
    $status         = $_POST['status'] ?? 'Borrowed';
    $date_returned  = $_POST['date_returned'] ?? '';

    $days_borrowed  = max(0, (int)($_POST['days_borrowed'] ?? 0));
    $daily_rate     = max(0.0, (float)($_POST['daily_rate'] ?? 0.0));
    $penalty_price  = max(0.0, (float)($_POST['penalty_price'] ?? 0.0));

    if ($status === 'Available') {
        $days_borrowed = 0;
        $borrow_fee    = 0.00;
    } else {
        $borrow_fee = $days_borrowed * $daily_rate;
    }

    $total_payment = $borrow_fee + $penalty_price;

    $borrow = "INSERT INTO borrowing_records
    (isbn, title, pages, year_published, status, date_returned, days_borrowed, daily_rate, penalty_price, borrow_fee, total_payment)
    VALUES
    (:isbn, :title, :pages, :year_published, :status, :date_returned, :days_borrowed, :daily_rate, :penalty_price, :borrow_fee, :total_payment)"; // <-- Added missing semicolon here

    $stmt = $pdo->prepare($borrow);
    $stmt->execute([
        ':isbn'           => $isbn, 
        ':title'          => $title, 
        ':pages'          => $pages, 
        ':year_published' => $year_published, 
        ':status'         => $status, 
        ':date_returned'  => $date_returned, 
        ':days_borrowed'  => $days_borrowed, 
        ':daily_rate'     => $daily_rate, 
        ':penalty_price'  => $penalty_price, 
        ':borrow_fee'     => $borrow_fee, 
        ':total_payment'  => $total_payment
    ]);

    // Store calculations in session for index.php receipt display
    $_SESSION['receipt'] = [
        'isbn'           => $isbn,
        'title'          => $title,
        'pages'          => $pages,
        'year_published' => $year_published,
        'status'         => $status,
        'date_returned'  => $date_returned,
        'days_borrowed'  => $days_borrowed,
        'daily_rate'     => $daily_rate,
        'penalty_price'  => $penalty_price,
        'borrow_fee'     => $borrow_fee,
        'total_payment'  => $total_payment
    ];

    // Redirect back to index.php to display receipt
    header("Location: index.php");
    exit();
}
?>