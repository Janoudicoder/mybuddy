<?php
require '../private/conn.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['betaling_id'])) {
    try {
        $betaling_id_from_get = $_GET['betaling_id'];
        $bedrag = $_GET['bedrag'];
        $leden = $_GET['leden'];
        $betaling_title = $_GET['betaling_title'];
        $groep_id = $_GET['groep_id'];
        //echo $betaling_title;

        $query_select_betaling = "SELECT * FROM betaling WHERE groep_betaling_id = :betaling_id";
        $stmt_select_betaling = $dbh->prepare($query_select_betaling);
        $stmt_select_betaling->bindParam(':betaling_id', $betaling_id_from_get);
        $stmt_select_betaling->execute();

        $existing_betaling = $stmt_select_betaling->fetch(PDO::FETCH_ASSOC);

        if (!$existing_betaling) {
            echo "Betaling with ID $betaling_id_from_get not found.";
            exit;
        }

        $dbh->beginTransaction();

        try {
            $aantal_gebruikers = $leden;
            $bedrag_per_gebruiker = round($bedrag / $aantal_gebruikers, 2);

            $query_update_betalingen = "UPDATE betalingen SET bedrag = :bedrag, betaling_title = :betali ng_title WHERE betalingen_id = :betaling_id";
            $stmt_update_betalingen = $dbh->prepare($query_update_betalingen);
            $stmt_update_betalingen->bindParam(':bedrag', $bedrag);
            $stmt_update_betalingen->bindParam(':betaling_id', $betaling_id_from_get);
            $stmt_update_betalingen->bindParam(':betaling_title', $betaling_title);
            $stmt_update_betalingen->execute();

            $query_update_betaling = "UPDATE betaling SET bedrag = :bedrag_per_gebruiker, beschrijving = :betaling_title WHERE groep_betaling_id = :betaling_id";
            $stmt_update_betaling = $dbh->prepare($query_update_betaling);
            $stmt_update_betaling->bindParam(':bedrag_per_gebruiker', $bedrag_per_gebruiker);
            $stmt_update_betaling->bindParam(':betaling_id', $betaling_id_from_get);
            $stmt_update_betaling->bindParam(':betaling_title', $betaling_title);
            $stmt_update_betaling->execute();

            $rowsAffected_betalingen = $stmt_update_betalingen->rowCount();
            $rowsAffected_betaling = $stmt_update_betaling->rowCount();

            $dbh->commit();

            if ($rowsAffected_betalingen > 0 && $rowsAffected_betaling > 0) {
                $_SESSION['melding'] = 'Betaling geupdatet!';

             //echo $betaling_title;
             //echo $query_update_betalingen;

             header("location: ../index.php?page=groepoverzicht&id=$groep_id");
            } else {
                echo 'No rows were affected. Check your update query conditions.';
            }
        } catch (PDOException $e) {
            $dbh->rollBack();
            throw $e;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo 'Invalid request.';
}
?>
