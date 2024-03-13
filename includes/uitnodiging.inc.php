<?php
require './private/conn.php';

if(isset($_SESSION['id'])) {
    $user_id = $_GET['user_id'];
    
    try {
        $invitations_query = "SELECT uitnodigingen.*, groepen.groep_title FROM uitnodigingen INNER JOIN groepen ON uitnodigingen.groep_id = groepen.groep_id WHERE uitnodigingen.user_id = :user_id AND uitnodigingen.status = 'uitgenodigd'";
        $invitations_stmt = $dbh->prepare($invitations_query);
        $invitations_stmt->bindParam(':user_id', $user_id);
        $invitations_stmt->execute();
        
        while($invitation_row = $invitations_stmt->fetch(PDO::FETCH_ASSOC)) {
            $invitation_id = $invitation_row['uitnodiging_id'];
            $groep_id = $invitation_row['groep_id'];
            $groep_title = $invitation_row['groep_title']; 
            
            ?>
            <body>
                <div class="container mt-5">
                    <h1>Uitnodiging voor groep "<?php echo $groep_title; ?>"</h1> 
                    <p>Je bent uitgenodigd om lid te worden van de groep <?php echo $groep_title; ?>.</p>
                    <form action="php/uitnodiging.php" method="post">
                        <input type="hidden" name="invitation_id" value="<?php echo $invitation_id; ?>">
                        <input type="hidden" name="groep_id" value="<?php echo $groep_id; ?>">
                        <button type="submit" class="btn btn-success" name="accept">Accepteren</button>
                        <button type="submit" class="btn btn-danger" name="decline">Weigeren</button>
                    </form>
                </div>
            </body>
            <?php
        }
        
    } catch(PDOException $e) {
        echo "Er is een fout opgetreden: " . $e->getMessage();
    }
} else {
    echo "Gebruiker is niet ingelogd.";
}
?>
