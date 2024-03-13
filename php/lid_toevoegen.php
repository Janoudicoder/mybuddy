<?php
require '../private/conn.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $voornaam = $_POST['voornaam'];
        $groep_naam = $_POST['groep_title']; 
        
        if (!$email) {
            $_SESSION['melding'] = 'Ongeldig e-mailadres.';
            header('location: ../index.php?page=Groepen');
            exit();
        }

       
        $user_query = "SELECT id FROM users WHERE email = :email";
        $user_stmt = $dbh->prepare($user_query);
        $user_stmt->bindParam(':email', $email);
        $user_stmt->execute();
        
        $user_id = $user_stmt->fetchColumn(); 

        if (!$user_id) {
            $_SESSION['melding'] = 'Gebruiker met dit e-mailadres bestaat niet.';
            header('location: ../index.php?page=Groepen');
            exit();
        }

        $groep_query = "SELECT groep_id FROM groepen WHERE groep_title = :groep_title";
        $groep_stmt = $dbh->prepare($groep_query);
        $groep_stmt->bindParam(':groep_title', $groep_naam);
        $groep_stmt->execute();

        $groep_id = $groep_stmt->fetchColumn(); 

        if (!$groep_id) {
            $_SESSION['melding'] = 'Geen groep gevonden met de opgegeven titel.';
            header('location: ../index.php?page=Groepen');
            exit();
        }

        $membership_query = "SELECT COUNT(*) FROM groep_gebruikers WHERE groep_id = :groep_id AND user_id = :user_id";
        $membership_stmt = $dbh->prepare($membership_query);
        $membership_stmt->bindParam(':groep_id', $groep_id);
        $membership_stmt->bindParam(':user_id', $user_id);
        $membership_stmt->execute();

        $membership_count = $membership_stmt->fetchColumn(); 

        if ($membership_count > 0) {
            $_SESSION['melding'] = 'Deze gebruiker is al lid van de groep.';
            header('location: ../index.php?page=Groepen');
            exit();
        }

        $invite_query = "SELECT COUNT(*) FROM uitnodigingen WHERE groep_id = :groep_id AND user_id = :user_id AND status = 'uitgenodigd'";
        $invite_stmt = $dbh->prepare($invite_query);
        $invite_stmt->bindParam(':groep_id', $groep_id);
        $invite_stmt->bindParam(':user_id', $user_id);
        $invite_stmt->execute();

        $invite_count = $invite_stmt->fetchColumn(); 

        if ($invite_count > 0) {
            $_SESSION['melding'] = 'Deze gebruiker is al uitgenodigd voor de groep.';
            header('location: ../index.php?page=Groepen');
            exit();
        }

        $add_invite_query = "INSERT INTO uitnodigingen (groep_id, user_id, status) VALUES (:groep_id, :user_id, 'uitgenodigd')";
        $add_invite_stmt = $dbh->prepare($add_invite_query);
        $add_invite_stmt->bindParam(':groep_id', $groep_id);
        $add_invite_stmt->bindParam(':user_id', $user_id);
        
        if ($add_invite_stmt->execute()) {   
            $_SESSION['melding'] = 'Uitnodiging verstuurd naar de gebruiker!';
            header('location: ../index.php?page=Groepen');
            exit();
        } else {
            $_SESSION['melding'] = 'Fout bij het versturen van de uitnodiging.';
            header('location: ../index.php?page=Groepen');
            exit();
        }
    }
} catch(PDOException $e) {
    $_SESSION['melding'] = 'Er is een fout opgetreden: ' . $e->getMessage();
    header('location: ../index.php?page=Groepen');
}
?>
