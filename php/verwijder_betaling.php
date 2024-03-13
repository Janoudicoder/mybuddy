<?php
require '../private/conn.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') { 
    if (isset($_GET['id'], $_GET['groep_id'])) {
        $betaling_id = $_GET['id'];
        $groep_id = $_GET['groep_id'];

        $logged_in_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $query_check_permission = "SELECT betaling_beheerder FROM betalingen WHERE betalingen_id = :betaling_id";
        $stmt_check_permission = $dbh->prepare($query_check_permission);
        $stmt_check_permission->bindParam(':betaling_id', $betaling_id);
        $stmt_check_permission->execute();
        $betaling_beheerder = $stmt_check_permission->fetchColumn();

        if ($logged_in_user_id === $betaling_beheerder) {
            $query_delete_payments = "DELETE FROM betaling WHERE groep_betaling_id = :betaling_groep_id";
            $stmt_delete_payments = $dbh->prepare($query_delete_payments);
            $stmt_delete_payments->bindParam(':betaling_groep_id', $betaling_id);
            $stmt_delete_payments->execute();

            $query_delete_betalingen = "DELETE FROM betalingen WHERE betalingen_id = :betaling_id";
            $stmt_delete_betalingen = $dbh->prepare($query_delete_betalingen);
            $stmt_delete_betalingen->bindParam(':betaling_id', $betaling_id);

            if ($stmt_delete_betalingen->execute()) {
                $_SESSION['melding'] = 'Betaling is verwijderd';
                header("location: ../index.php?page=groepoverzicht&id=$groep_id");
                exit();
            } else {
                echo "Error deleting payment from betalingen table.";
            }
        } else {
            echo "Unauthorized to delete this payment.";
        }
    } else {
        echo "Invalid request. Missing parameters.";
    }
} else {
    echo "Invalid request method.";
}
?>
