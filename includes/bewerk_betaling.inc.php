<?php
require './private/conn.php';

if (isset($_GET['id'])) {
    $betaling_id = $_GET['id'];
    $gebruikers = $_GET['leden'];
    

    $query_payment = "SELECT * FROM betalingen WHERE betalingen_id = :betaling_id";
    $stmt_payment = $dbh->prepare($query_payment);
    $stmt_payment->bindParam(':betaling_id', $betaling_id);
    $stmt_payment->execute();

    $paymentDetails = $stmt_payment->fetch(PDO::FETCH_ASSOC);

    if ($paymentDetails) {
        $query_users = "SELECT id, voornaam, achternaam FROM users";
        $stmt_users = $dbh->prepare($query_users);
        $stmt_users->execute();

        ?>
        <html>
        
        <body>
            <div class="container mt-5">
                <h1>Edit Payment</h1>
                <form action="php/bewerk_betaling.php" method="get" class="mt-4">
                <label for="betaling_title">Payment Title:</label>
                <input type="text" class="form-control" name="betaling_title" value="<?= htmlspecialchars($paymentDetails['betaling_title']) ?>">
                <label for="bedrag">Bedrag:</label>
                <input type="text" class="form-control" name="bedrag" value="<?= htmlspecialchars($paymentDetails['bedrag']) ?>">

                <input type="hidden" name="betaling_id" value="<?= $paymentDetails['betalingen_id'] ?>">
                <input type="hidden" name="groep_id" value="<?= $paymentDetails['groep_id'] ?>">
                <input type="hidden" name="leden" value="<?= $gebruikers ?>">

                <input type="submit" class="form-check-label" value="Update Payment">
            </form>

            </div>

        </body>
        </html>
        <?php
    } else {
        echo "Payment not found.";
    }
} else {
    echo "Payment ID not specified.";
}
?>
