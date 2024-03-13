<?php
require '../private/conn.php';

if (isset($_GET['koppeling_id'])) {
    $koppeling_id = $_GET['koppeling_id'];

    $query_verwijder_koppeling = "DELETE FROM groep_gebruikers WHERE id = :koppeling_id";

    $stmt_verwijder_koppeling = $dbh->prepare($query_verwijder_koppeling);
    $stmt_verwijder_koppeling->bindParam(':koppeling_id', $koppeling_id);

    try {
        $stmt_verwijder_koppeling->execute();
        $_SESSION['melding'] = 'Gebruiker is verwijdert';
        header('location: ../index.php?page=Groepen');
        exit();
    } catch (PDOException $e) {
        echo "Er is een fout opgetreden: " . $e->getMessage();
    }
} else {
    echo "Geen koppeling ID gevonden om te verwijderen";
}

