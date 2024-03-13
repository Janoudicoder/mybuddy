<?php
include '../private/conn.php';

if(isset($_POST['invitation_id'])) {
    $invitation_id = $_POST['invitation_id'];
    
    try {
        $invite_query = "SELECT * FROM uitnodigingen WHERE uitnodiging_id = :invitation_id";
        $invite_stmt = $dbh->prepare($invite_query);
        $invite_stmt->bindParam(':invitation_id', $invitation_id);
        $invite_stmt->execute();
        
        $invite_row = $invite_stmt->fetch(PDO::FETCH_ASSOC);
        
        if($invite_row) {
            $groep_id = $invite_row['groep_id'];
            $user_id = $invite_row['user_id']; 
            
            if(isset($_POST['accept'])) {
                $toevoegen_query = "INSERT INTO groep_gebruikers (groep_id, user_id) VALUES (:groep_id, :user_id)";
                $toevoegen_stmt = $dbh->prepare($toevoegen_query);
                $toevoegen_stmt->bindParam(':groep_id', $groep_id);
                $toevoegen_stmt->bindParam(':user_id', $user_id);
                
                if ($toevoegen_stmt->execute()) {
                    $update_invite_query = "UPDATE uitnodigingen SET status = 'geaccepteerd' WHERE uitnodiging_id = :invitation_id";
                    $update_invite_stmt = $dbh->prepare($update_invite_query);
                    $update_invite_stmt->bindParam(':invitation_id', $invitation_id);
                    $update_invite_stmt->execute();
                    
                    $_SESSION['melding'] = 'Uitnodiging geaccepteerd! Je bent toegevoegd aan de groep.';
                    header('location: ../index.php?page=Groepen');
                    exit();
                } else {
                    echo "Fout bij het toevoegen van de gebruiker aan de groep.";
                }
            }
            
            if(isset($_POST['decline'])) {
                $update_invite_query = "UPDATE uitnodigingen SET status = 'geweigerd' WHERE uitnodiging_id = :invitation_id";
                $update_invite_stmt = $dbh->prepare($update_invite_query);
                $update_invite_stmt->bindParam(':invitation_id', $invitation_id);
                $update_invite_stmt->execute();
                
                $_SESSION['melding'] = 'Uitnodiging geweigerd.';
                header('location: ../index.php?page=Groepen');
                exit();
            }
        }
    } catch(PDOException $e) {
        echo "Er is een fout opgetreden: " . $e->getMessage();
    }
}
?>
