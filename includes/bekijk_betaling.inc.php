<?php
require './private/conn.php';

if (isset($_SESSION['melding'])) {
    echo '<p style="color: red;">' . $_SESSION['melding'] . '</p>';
    unset($_SESSION['melding']);
}

if (isset($_GET['id'])) {
    $betalingen_id = $_GET['id'];
    $groep_id = $_GET['groep_id'];

    $query_openstaande_betalingen = "SELECT betaling.groep_betaling_id, betaling.betaling_id, betaling.bedrag, betaling.beschrijving, users.voornaam, users.achternaam, users.id AS gebruiker_id, betaling.is_betaald 
    FROM betaling 
    INNER JOIN users ON betaling.gebruiker_id = users.id 
    WHERE betaling.groep_betaling_id = :id";

    $stmt_openstaande_betalingen = $dbh->prepare($query_openstaande_betalingen);
    $stmt_openstaande_betalingen->bindParam(':id', $betalingen_id);
    $stmt_openstaande_betalingen->execute();

    $result = $stmt_openstaande_betalingen->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_SESSION['id'])) {
        echo '<table class="table" id="betaling_user">
                <thead>
                    <tr>
                        <th>Voornaam</th>
                        <th>acheternaam</th>
                        <th>Bedrag</th>
                        <th>Beschrijving</th>
                    </tr>
                </thead>
                <tbody>';

        if ($result) {
            foreach ($result as $row_betaling) {
                echo '<tr>';
                echo '<td>' . $row_betaling['voornaam'] . '</td>';
                echo '<td>' . $row_betaling['achternaam'] . '</td>';
                echo '<td>' . $row_betaling['bedrag'] . '</td>';
                echo '<td>' . $row_betaling['beschrijving'] . '</td>';
                

                $userid = $row_betaling['gebruiker_id'];
                $logged_in_user_id = $_SESSION['id'];

                echo '<td>';

                if ($row_betaling['is_betaald'] == 0) {
                    echo '<td>';
                
                    if ($userid === $logged_in_user_id) {
                        echo '<a href="php/markeer_betaling.php?betaling_id=' . $row_betaling['betaling_id'] . '&gebruiker_id=' . $row_betaling['gebruiker_id'] . '&bedrag=' . $row_betaling['bedrag'] . '&betalingen_id=' . $row_betaling['groep_betaling_id'] . '&groep_id=' . $groep_id . '"><i class="fa-solid fa-money-bill"></i> Betalen</a>';
                    } else {
                        echo '<i class="fa-solid fa-ban"></i> nog niet betaalt';
                    }
                
                    echo '</td>';
                } else {
                    if ($row_betaling['is_betaald'] == 0) {
                        echo '<td><i class="fa-solid fa-ban"></i> nog niet betaalt</td>';
                    } else {
                        echo '<td><i class="fa-solid fa-circle-dollar-to-slot"></i> Betaald</td>';
                    }
                }

                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo "<tr><td colspan='5'>Geen openstaande betalingen gevonden.</td></tr>";
        }

        echo '</tbody></table>';
    } else {
        echo "?";
    }
} else {
    echo "no id ";
}
?>
