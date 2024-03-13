<?php
require './private/conn.php';

if (isset($_GET['groep_id'])) {
    $groep_id = $_GET['groep_id'];

    $query_gebruikers = "SELECT users.id, users.voornaam, users.achternaam
                    FROM users 
                    INNER JOIN groep_gebruikers ON users.id = groep_gebruikers.user_id 
                    WHERE groep_gebruikers.groep_id = :groep_id";

    $stmt_gebruikers = $dbh->prepare($query_gebruikers);
    $stmt_gebruikers->bindParam(':groep_id', $groep_id);
    $stmt_gebruikers->execute();
    ?>
    
    <div class="container mt-5">
        <form action="php/voeg_betaling.php" method="POST" class="mt-4">
        <div class="mb-3">
    <label for="bedrag" class="form-label">Bedrag:</label>
    <input type="text" name="bedrag" id="bedrag" class="form-control" required>
</div>

            <div class="mb-3">
                <label for="beschrijving" class="form-label">Beschrijving:</label>
                <input type="text" name="beschrijving" id="beschrijving" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="gebruikers" class="form-label">Selecteer leden:</label><br>
                <?php
                while ($row_gebruiker = $stmt_gebruikers->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="form-check">';
                    echo '<input type="checkbox" name="gebruikers[]" value="' . $row_gebruiker['id'] . '" class="form-check-input">';
                    echo '<label class="form-check-label">' . $row_gebruiker['voornaam'] . ' ' . $row_gebruiker['achternaam'] . '</label>';
                    echo '</div>';
                }
                ?>
            </div>
            <input type="hidden" name="groep_id" value="<?= $groep_id ?>">
            <button type="submit" class="btn btn-outline-dark">Verwerk betaling</button>
        </form>
    </div>
    <?php
} else {
    echo "Geen groep geselecteerd";
}
?>
