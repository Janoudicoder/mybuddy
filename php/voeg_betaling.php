<?php
require '../private/conn.php';

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['bedrag', 'beschrijving', 'gebruikers', 'groep_id'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            echo "Niet alle vereiste velden zijn ingevuld. Ontbrekend veld: $field";
            exit;
        }
    }

    $bedrag = $_POST['bedrag'];
    $beschrijving = $_POST['beschrijving'];
    $gebruikers = $_POST['gebruikers'];
    $groep_id = $_POST['groep_id'];

    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

    if ($user_id === null) {
        echo "Geen gebruikers-ID gevonden in de sessie.";
        exit;
    }

    $aantal_gebruikers = count($gebruikers);
    $query_toevoegen_betalingen = "INSERT INTO betalingen (bedrag, betaling_title, leden, groep_id, betaling_beheerder) VALUES (:bedrag, :beschrijving, :gebruikers, :groep_id, :betaling_beheerder)";
    $stmt_toevoegen_betalingen = $dbh->prepare($query_toevoegen_betalingen);
    $stmt_toevoegen_betalingen->bindParam(':bedrag', $bedrag);
    $stmt_toevoegen_betalingen->bindParam(':beschrijving', $beschrijving); 
    $stmt_toevoegen_betalingen->bindParam(':gebruikers', $aantal_gebruikers);
    $stmt_toevoegen_betalingen->bindParam(':groep_id', $groep_id);
    $stmt_toevoegen_betalingen->bindParam(':betaling_beheerder', $user_id);

    $stmt_toevoegen_betalingen->execute();

    
        $lastInsertedId = $dbh->lastInsertId();

        if ($aantal_gebruikers > 0) {
            $bedrag_per_gebruiker = round($bedrag / $aantal_gebruikers, 2);
            foreach ($gebruikers as $gebruiker_id) {
                $query_toevoegen_betaling = "INSERT INTO betaling (gebruiker_id, bedrag, beschrijving, groep_id, groep_betaling_id) VALUES (:gebruiker_id, :bedrag, :beschrijving, :groep_id, :groep_betaling_id)";
                $stmt_toevoegen_betaling = $dbh->prepare($query_toevoegen_betaling);
                $stmt_toevoegen_betaling->bindParam(':gebruiker_id', $gebruiker_id);
                $stmt_toevoegen_betaling->bindParam(':bedrag', $bedrag_per_gebruiker);
                $stmt_toevoegen_betaling->bindParam(':beschrijving', $beschrijving);
                $stmt_toevoegen_betaling->bindParam(':groep_id', $groep_id);
                $stmt_toevoegen_betaling->bindParam(':groep_betaling_id', $lastInsertedId);

                $stmt_toevoegen_betaling->execute();
            }

            $_SESSION['melding'] = 'Betaling toegevoegd!';
            header("location: ../index.php?page=groepoverzicht&id=$groep_id");
        } else {
            $_SESSION['melding'] = 'Geen gebruikers geselecteerd';
            header('location: ../index.php?page=betaling_toevoegen');
        }
    
} else {
    echo "Formulier niet verzonden.";
}
?>
