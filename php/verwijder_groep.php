<?php
session_start(); 

require '../private/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $groep_id = $_GET['id'];

    try {
        $check_users_query = "SELECT COUNT(*) FROM groep_gebruikers WHERE groep_id = :groep_id";
        $stmt_check_users = $dbh->prepare($check_users_query);
        $stmt_check_users->bindParam(':groep_id', $groep_id);
        $stmt_check_users->execute();
        $user_count = $stmt_check_users->fetchColumn();

        if ($user_count > 1) { 
            $_SESSION['melding'] = 'Kan de groep niet verwijderen omdat er gebruikers aan zijn toegewezen.';
            header('location: ../index.php?page=Groepen');
            exit;
        } else {
            $delete_users_query = "DELETE FROM groep_gebruikers WHERE groep_id = :groep_id";
            $stmt_users = $dbh->prepare($delete_users_query);
            $stmt_users->bindParam(':groep_id', $groep_id);
            $stmt_users->execute();

            $delete_query = "DELETE FROM groepen WHERE groep_id = :groep_id";
            $stmt = $dbh->prepare($delete_query);
            $stmt->bindParam(':groep_id', $groep_id);

            if ($stmt->execute()) {
                $_SESSION['melding'] = 'De groep is succesvol verwijderd!';
                header('location: ../index.php?page=Groepen');
                exit;
            } else {
                echo "Er is een fout opgetreden bij het verwijderen van de groep.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Ongeldige aanvraag";
}
?>
