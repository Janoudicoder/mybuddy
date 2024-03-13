<?php
require './private/conn.php';

if (isset($_SESSION['melding'])) {
    echo '<p style="color: red;">' . $_SESSION['melding'] . '</p>';
    unset($_SESSION['melding']);
}
$user_id = $_SESSION['id'];

$query_count_invitations = "SELECT COUNT(*) AS invitation_count FROM uitnodigingen WHERE user_id = :user_id AND status = 'uitgenodigd'";
$stmt_count_invitations = $dbh->prepare($query_count_invitations);
$stmt_count_invitations->bindParam(':user_id', $user_id);
$stmt_count_invitations->execute();
$invitation_count_result = $stmt_count_invitations->fetch(PDO::FETCH_ASSOC);
$invitation_count = $invitation_count_result['invitation_count'];

$query_gebruiker_groepen = "SELECT groepen.groep_id, groepen.groep_plaatje, groepen.groep_title, groepen.groep_doel, groepen.groep_datum, groep_gebruikers.role
                            FROM groepen
                            INNER JOIN groep_gebruikers ON groepen.groep_id = groep_gebruikers.groep_id
                            WHERE groep_gebruikers.user_id = :user_id";

$stmt_gebruiker_groepen = $dbh->prepare($query_gebruiker_groepen);
$stmt_gebruiker_groepen->bindParam(':user_id', $user_id);
$stmt_gebruiker_groepen->execute();
$result = $stmt_gebruiker_groepen->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row mb-3">
            <div class="col text-end position-relative">
                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Groep toevoegen
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="index.php?page=maak_groep"><i class="fa-solid fa-user-group"></i>Maak groep</a></li>
                        <li><a class="dropdown-item" href="index.php?page=uitnodiging&user_id=<?php echo $user_id; ?>"><i class="fa-solid fa-envelope"></i> Uitnodigingen <?php echo ($invitation_count > 0) ? '<span class="badge bg-danger">' . $invitation_count . '</span>' : ''; ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



    

<div class="album py-5 bg-light">
    <div class="container">
        <div class="row">
            <?php
            if (count($result) > 0) {
                foreach ($result as $row) {
                    echo '
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <img src="data:image/jpeg;base64,' . base64_encode($row["groep_plaatje"]) . '" class="card-img-top" alt="Afbeelding">
                            <div class="card-body">
                            
                                <p class="card-text">' . $row["groep_title"] . '</p>
                                <p class="card-text">' . $row["groep_doel"] . '</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">';
                    
                    if ($row["role"] == "Admin") {
                        echo '
                                        <button type="button" class="btn btn-sm btn-outline-secondary">
                                            <a href="index.php?page=groepoverzicht&id=' . $row['groep_id'] . '" class="btn-link" target="_blank">Bekijk</a>
                                        </button>
                                        <a href="index.php?page=edit&id=' . $row["groep_id"] . '" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Bewerk
                                        </a>
                                        <a href="php/verwijder_groep.php?id=' . $row["groep_id"] . '" class="btn btn-sm btn-outline-danger" onclick="return confirmDelete();">
                                            <i class="bi bi-trash"></i> Verwijder
                                        </a>';
                    } else {
                        echo '
                                        <button type="button" class="btn btn-sm btn-outline-secondary">
                                            <a href="index.php?page=groepoverzicht&id=' . $row['groep_id'] . '" class="btn-link" target="_blank">Bekijk</a>
                                        </button>';
                    }
                    
                    echo '
                                    </div>
                                    <small class="text-muted">Geplaatst op: ' . $row["groep_datum"] . '</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                }
            } else {
                echo "Geen resultaten gevonden";
            }
            ?>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>