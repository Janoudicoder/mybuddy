<?php
require '../private/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $groep_plaatje = file_get_contents($_FILES['groep_plaatje']['tmp_name']);
        $groep_title = $_POST['groep_title'];
        $groep_doel = $_POST['groep_doel'];
       

        $currentDate = date('Y-m-d');

        session_start();
        $user_id = $_SESSION['id'];

       
        $stmt = $dbh->prepare("INSERT INTO groepen (groep_plaatje, groep_title, groep_doel, groep_datum, beheerder_id) VALUES (:groep_plaatje, :groep_title, :groep_doel, :groep_datum, :beheerder_id)");

        $stmt->bindParam(':groep_plaatje', $groep_plaatje, PDO::PARAM_LOB);
        $stmt->bindParam(':groep_title', $groep_title);
        $stmt->bindParam(':groep_doel', $groep_doel);
        $stmt->bindParam(':groep_datum', $currentDate);
        $stmt->bindParam(':beheerder_id', $user_id);

        $stmt->execute();       

        
        $last_group_id = $dbh->lastInsertId();

        
        $stmt_add_admin = $dbh->prepare("INSERT INTO groep_gebruikers (groep_id, user_id, role) VALUES (:groep_id, :user_id, 'Admin')");
        $stmt_add_admin->bindParam(':groep_id', $last_group_id);
        $stmt_add_admin->bindParam(':user_id', $user_id);
        $stmt_add_admin->execute();

        
        $_SESSION['melding'] = 'De groep is succesvol toegevoegd!';
        
        header('location: ../index.php?page=groepoverzicht&id=' . $last_group_id);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }   
}
?>
