<?php
require '../private/conn.php';

if (isset($_POST['submit'])) {
    $groep_id = $_POST['groep_id'];
    $groep_title = $_POST['groep_title'];
    $groep_doel = $_POST['groep_doel'];

    $query = "UPDATE groepen SET groep_title = :groep_title, groep_doel = :groep_doel WHERE groep_id = :groep_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':groep_id', $groep_id);
    $stmt->bindParam(':groep_title', $groep_title);
    $stmt->bindParam(':groep_doel', $groep_doel);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['melding'] = "Groep bewerkt";
        header('location: ../index.php?page=Groepen');
        exit();
    } else {
        echo "Er is een fout opgetreden bij het bewerken van de groep";
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
 