<?php
require './private/conn.php';

if(isset($_GET['id'])) {
    $groep_id = $_GET['id'];

    $query = "SELECT groep_id, groep_plaatje, groep_title, groep_doel, groep_datum FROM groepen WHERE groep_id = :groep_id";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':groep_id', $groep_id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: ../index.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="php/edit.php" method="POST">
                <input type="hidden" name="groep_id" value="<?php echo $row['groep_id']; ?>">
                <div class="mb-3">
                    <label for="groep_title" class="form-label">Titel:</label>
                    <input type="text" class="form-control" id="groep_title" name="groep_title" value="<?php echo $row['groep_title']; ?>">
                </div>
                <div class="mb-3">
                    <label for="groep_doel" class="form-label">Doel:</label>
                    <textarea class="form-control" id="groep_doel" name="groep_doel"><?php echo $row['groep_doel']; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Opslaan</button>
            </form>
        </div>
    </div>
</div>
