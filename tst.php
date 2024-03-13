<?php
require './private/conn.php';

// Controleer of de gebruiker is ingelogd en haal zijn gebruikers-ID op
// Dit vereist een manier om de gebruiker in te loggen en de gebruikers-ID te verkrijgen
// Laten we aannemen dat de gebruikers-ID beschikbaar is in $_SESSION['user_id']
if(isset($_SESSION['id'])) {
    $user_id = $_GET['user_id'];
    
    
    try {
        // Haal alle uitnodigingen op voor de ingelogde gebruiker
        $invitations_query = "SELECT uitnodigingen.*, groepen.groep_title FROM uitnodigingen INNER JOIN groepen ON uitnodigingen.groep_id = groepen.groep_id WHERE uitnodigingen.user_id = :user_id";
        $invitations_stmt = $dbh->prepare($invitations_query);
        $invitations_stmt->bindParam(':user_id', $user_id);
        $invitations_stmt->execute();
        
        // Loop door alle uitnodigingen en geef ze weer
        while($invitation_row = $invitations_stmt->fetch(PDO::FETCH_ASSOC)) {
            $invitation_id = $invitation_row['uitnodiging_id'];
            $groep_id = $invitation_row['groep_id'];
            $groep_title = $invitation_row['groep_title']; // Haal de groepstitel op
            // Andere relevante velden kunnen ook worden opgehaald
            
            // Geef de gebruiker de mogelijkheid om de uitnodiging te accepteren of te weigeren
            ?>
            <body>
                <div class="container mt-5">
                    <h1>Uitnodiging voor groep "<?php echo $groep_title; ?>"</h1> <!-- Hier wordt de groepstitel weergegeven -->
                    <p>Je bent uitgenodigd om lid te worden van de groep <?php echo $groep_title; ?>.</p>
                    <form method="post">
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
