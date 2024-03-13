<?php
if (isset($_SESSION['melding'])) {
    echo '<p style="color: red;">' . $_SESSION['melding'] . '</p>';
    unset($_SESSION['melding']);
  }
require './private/conn.php';

if (isset($_GET['id'])) {
    $groep_id = $_GET['id'];
     

    $query = "SELECT groep_id, groep_plaatje, groep_title, groep_doel, beheerder_id, groep_datum FROM groepen WHERE groep_id = :groep_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':groep_id', $groep_id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $beheerder_idd = $row['beheerder_id'];
        
        $query_user = "SELECT voornaam, achternaam FROM users WHERE id = :beheerder_id";
        $stmt_user = $dbh->prepare($query_user);
        $stmt_user->bindParam(':beheerder_id', $beheerder_idd);
        $stmt_user->execute();
        
        $row_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

        ?>
        
        <body class="sb-nav-fixed">
        
            
                    <div id="layoutSidenav_content">    
                        <main>
                            <div class="container-fluid px-4">
                            <div class="container">
                        <h1><?= $row["groep_title"] ?></h1>
                    </div>
                    <div class="card" style="background-image: url('data:image/jpeg;base64,<?= base64_encode($row["groep_plaatje"]) ?>'); background-size: cover; background-position: center;">
                        <div class="card-body">
                            <p class="card-text"><?= $row["groep_doel"] ?></p>
                            <p class="card-text">Geplaatst op: <?= $row["groep_datum"] ?></p>
                            <p class="card-text">Gemaakt door: <?= $row_user["voornaam"] . " " . $row_user["achternaam"] ?></p>                    
                        </div>
                    </div>
                            
                            <br>
                        
                                
                                    <div class="col-xl-6">
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <i class="fa-solid fa-euro-sign"></i>
                                                betalingen
                                                <a href="index.php?page=betaling_toevoegen&groep_id=<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>&user_id=<?= $_SESSION['id'] ?>" class="btn btn-outline-dark btn-lg px-5">
                                                    <i class="fas fa-plus me-1"></i> Voeg betaling toe
                                                </a>


                                            </div>
                                            <div class="card-body">
                                                <table class="table" id="openstaande_betalingen">
                                                    <thead>
                                                        <tr>
                                                            <th>betaling title</th>
                                                            <th>moeten nog betalen </th>
                                                            <th>Bedrag</th>
                                                            <th>bekijk</th>
                                                            <th>betalen aan</th>
                                                            

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $query_openstaande_betalingen = "SELECT betalingen.betalingen_id, betalingen.betaling_title, betalingen.betaling_beheerder, betalingen.leden, betalingen.bedrag, betalingen.groep_id, betalingen.is_betaalt
                                                    FROM betalingen
                                                    WHERE betalingen.groep_id = :groep_id AND betalingen.is_betaalt = 0";   

                                                    $stmt_openstaande_betalingen = $dbh->prepare($query_openstaande_betalingen);
                                                    $stmt_openstaande_betalingen->bindParam(':groep_id', $groep_id);
                                                    $stmt_openstaande_betalingen->execute();

                                                    if (isset($_SESSION['id'])) {
                                                        $logged_in_user_id = $_SESSION['id'];

                                                        if ($stmt_openstaande_betalingen) {
                                                            while ($row_betaling = $stmt_openstaande_betalingen->fetch(PDO::FETCH_ASSOC)) {
                                                            
                                                                echo '<tr>';
                                                                echo '<td>' . $row_betaling['betaling_title'] . '</td>';
                                                                echo '<td>' . $row_betaling['leden'] . '</td>';
                                                                echo '<td>' . $row_betaling['bedrag'] . '</td>';
                                                                

                                                                echo '<td><a href="index.php?page=bekijk_betaling&id=' . $row_betaling['betalingen_id'] . '&groep_id=' . $groep_id . '"><i class="fas fa-eye"></i> Bekijk</a></td>';
                                                                if ($logged_in_user_id === $row_betaling['betaling_beheerder']) {

                                                                    $query_beheerder = "SELECT voornaam, achternaam FROM users WHERE id = :id";
                                                                    $stmt_beheerder = $dbh->prepare($query_beheerder);
                                                                    $stmt_beheerder->bindParam(':id', $row_betaling['betaling_beheerder']);
                                                                    $stmt_beheerder->execute();
                                                                    $row_beheerder = $stmt_beheerder->fetch(PDO::FETCH_ASSOC);
                                                              

                                                                    echo '<td>' . $row_beheerder['voornaam'] . ' ' . $row_beheerder['achternaam'] . '</td>';
                                                                    echo '<td><a href="index.php?page=bewerk_betaling&id=' . $row_betaling['betalingen_id'] . '&groep_id=' . $groep_id . '&leden=' . $row_betaling['leden'] . '"><i class="fa-solid fa-pen-to-square"></i> Bewerk</a></td>';
                                                                    echo '<td><a href="php/verwijder_betaling.php?id=' . $row_betaling['betalingen_id'] . '&groep_id=' . $groep_id . '"><i class="fa-solid fa-trash"></i> Verwijder</a></td>';
                                                                } else {
                                                                    $query_beheerder = "SELECT voornaam, achternaam FROM users WHERE id = :id";
                                                                    $stmt_beheerder = $dbh->prepare($query_beheerder);
                                                                    $stmt_beheerder->bindParam(':id', $row_betaling['betaling_beheerder']);
                                                                    $stmt_beheerder->execute();
                                                                    $row_beheerder = $stmt_beheerder->fetch(PDO::FETCH_ASSOC);
                                                           

                                                                    echo '<td>' . $row_beheerder['voornaam'] . ' ' . $row_beheerder['achternaam'] . '</td>';
                                                                
                                                                    echo '<td></td>';
                                                                
                                                                    echo '<td></td>';
                                                                }

                                                                echo '</tr>';
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='4'>Geen openstaande betalingen gevonden.</td></tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='4'>Gebruiker niet ingelogd.</td></tr>";
                                                    }
                                                    ?>



                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php
// Initialiseer de $groep_balans array
$groep_balans = array();

// Query om betalingen per gebruiker op te halen
$query_betalingen_per_gebruiker = "SELECT betalingen.betaling_beheerder, SUM(betalingen.bedrag) AS totaal_bedrag
                                   FROM betalingen
                                   WHERE betalingen.groep_id = :groep_id AND betalingen.is_betaalt = 0
                                   GROUP BY betalingen.betaling_beheerder";

$stmt_betalingen_per_gebruiker = $dbh->prepare($query_betalingen_per_gebruiker);
$stmt_betalingen_per_gebruiker->bindParam(':groep_id', $groep_id);
$stmt_betalingen_per_gebruiker->execute();

while ($row_betaling_per_gebruiker = $stmt_betalingen_per_gebruiker->fetch(PDO::FETCH_ASSOC)) {
    $gebruiker_id = $row_betaling_per_gebruiker['betaling_beheerder'];
    $totaal_bedrag = $row_betaling_per_gebruiker['totaal_bedrag'];
    
    // Voeg de balans toe aan de $groep_balans array
    $groep_balans[$gebruiker_id] = $totaal_bedrag;
}

?>

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-chart-line"></i>
                        Groep balans
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php
                            if (!empty($groep_balans)) {
                                foreach ($groep_balans as $gebruiker_id => $balans) {
                                    $query_gebruiker_info = "SELECT voornaam, achternaam FROM users WHERE id = :gebruiker_id";
                                    $stmt_gebruiker_info = $dbh->prepare($query_gebruiker_info);
                                    $stmt_gebruiker_info->bindParam(':gebruiker_id', $gebruiker_id);
                                    $stmt_gebruiker_info->execute();
                                    $row_gebruiker_info = $stmt_gebruiker_info->fetch(PDO::FETCH_ASSOC);
                                    
                                    $voornaam = $row_gebruiker_info['voornaam'];
                                    $achternaam = $row_gebruiker_info['achternaam'];
                                    
                                    echo "<li class='list-group-item'>$voornaam $achternaam: €$balans</li>";
                                }
                            } else {
                                echo "<li class='list-group-item'>Geen balansinformatie beschikbaar</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                    <i class="fa-solid fa-user"></i>
                                        Gebruikers 
                                        <a href="index.php?page=lid_toevoegen&groep_id=<?= $groep_id ?>&groep_title=<?= urlencode($row['groep_title']) ?>" class="btn btn-outline-dark btn-lg px-5" style="margin-top: 20px;">
                                            <i class="bi bi-plus"></i> Lid Toevoegen
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <table class="table" id="gebruikers">
                                            <thead>
                                                <tr>
                                                    <th>Voornaam</th>
                                                    <th>Achternaam</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_gebruikers = "SELECT users.voornaam, users.achternaam, groep_gebruikers.id AS koppeling_id, groep_gebruikers.role
                                                FROM users 
                                                INNER JOIN groep_gebruikers ON users.id = groep_gebruikers.user_id 
                                                WHERE groep_gebruikers.groep_id = :groep_id";
                    
                                                    $stmt_gebruikers = $dbh->prepare($query_gebruikers);
                                                    $stmt_gebruikers->bindParam(':groep_id', $groep_id);
                                                    $stmt_gebruikers->execute();

                                                echo '<tbody>';
                                                while ($row_gebruiker = $stmt_gebruikers->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<td>' . $row_gebruiker['voornaam'] . '</td>';
                                                    echo '<td>' . $row_gebruiker['achternaam'] . '</td>';
                                                    $Admin_id = $row['beheerder_id'];
                                                    $logged_in_user_id = $_SESSION['id'];

                                                    
                                                    if($logged_in_user_id === $Admin_id){
                                                        if ($row_gebruiker['role'] === 'user') {
                                                           
                                                            echo '<td><a href="php/verwijder_gebruiker.php?koppeling_id=' . $row_gebruiker['koppeling_id'] . '"><i class="fa-solid fa-user-xmark"></i></a></td>';
                                                        }
                                                    }
                                                   
                                                    echo '</tr>';
                                                }
                                                echo '</tbody>';
                                                ?>
                                            </tbody>
                                    </table>
                                </div>          
                                </div>

                            </div>
                        </main>
                    
                    </div>
                </div>
          </body>
        
       
            <?php
    } else {
        echo "Geen informatie gevonden voor deze groep";
    }
} else {
    echo "Geen groep geselecteerd";
}
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

