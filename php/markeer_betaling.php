<?php
require '../private/conn.php';

try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $betalingen_id = $_GET['betalingen_id'];
    $groep_id = $_GET['groep_id'];
   
    if (isset($_GET['betaling_id']) && isset($_GET['gebruiker_id'])) {
        $betaling_id = $_GET['betaling_id'];
        $gebruiker_id = $_GET['gebruiker_id'];
        $amount_paid = $_GET['bedrag'];
        
       
       

        try {
            $updateQuery = "UPDATE betaling SET is_betaald = 1 WHERE betaling_id = :betaling_id AND gebruiker_id = :gebruiker_id";
            $stmt = $dbh->prepare($updateQuery);

            if (!$stmt) {
                die("Error in preparing the statement: " . $dbh->errorInfo()[2]);
            }

            $stmt->bindParam(':betaling_id', $betaling_id, PDO::PARAM_INT);
            $stmt->bindParam(':gebruiker_id', $gebruiker_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $sqlCheckPayments = "SELECT COUNT(*) AS total FROM betaling WHERE groep_betaling_id = :betalingen_id AND is_betaald = 1";
                $stmtCheckPayments = $dbh->prepare($sqlCheckPayments);
                $stmtCheckPayments->bindParam(':betalingen_id', $betalingen_id);
                $stmtCheckPayments->execute();
                $resultCheckPayments = $stmtCheckPayments->fetch(PDO::FETCH_ASSOC);
                $_total = $resultCheckPayments['total'];
            
                $sqlGetUsers = "SELECT count(gebruiker_id) AS g FROM betaling WHERE groep_betaling_id = :betalingen_id";
                $stmtGetUsers = $dbh->prepare($sqlGetUsers);
                $stmtGetUsers->bindParam(':betalingen_id', $betalingen_id);
                $stmtGetUsers->execute();
                $users = $stmtGetUsers->fetch(PDO::FETCH_ASSOC);
                $usr = $users['g'];

              


                
              
                if ($_total === $usr) {
                   
                    $sqlUpdateIsBetaalt = "UPDATE betalingen SET is_betaalt = 1 WHERE betalingen_id = :betalingen_id";
                    $stmtUpdateIsBetaalt = $dbh->prepare($sqlUpdateIsBetaalt);
                    $stmtUpdateIsBetaalt->bindParam(':betalingen_id', $betalingen_id);
                    $stmtUpdateIsBetaalt->execute();
            
                    $_SESSION['melding'] = 'Iedereen heeft betaald';
                    header("location: ../index.php?page=groepoverzicht&id=$groep_id");
                } else {
                    $sqlUpdateLeden = "UPDATE betalingen SET leden = leden - 1, bedrag = bedrag - :amount_paid WHERE betalingen_id = :betalingen_id";
                    $stmtUpdateLeden = $dbh->prepare($sqlUpdateLeden);
                    $stmtUpdateLeden->bindParam(':amount_paid', $amount_paid);  
                    $stmtUpdateLeden->bindParam(':betalingen_id', $betalingen_id); 
                    $stmtUpdateLeden->execute();
                    
                    $_SESSION['melding'] = 'Betaling gelukt!';
                    header("location: ../index.php?page=groepoverzicht&id=$groep_id");
                }
                $stmt->closeCursor();
            } else {
                echo "Error marking payment as paid: " . $stmt->errorInfo()[2];
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } else {
        echo "No betaling_id or gebruiker_id provided";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$dbh = null;
?>
