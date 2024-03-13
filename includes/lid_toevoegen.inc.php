<div class="container mt-5">
    <h1>Lid Toevoegen</h1>
    <form action="php/lid_toevoegen.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="voornaam" class="form-label">Voornaam:</label>
            <input type="text" class="form-control" id="voornaam" name="voornaam" required>
        </div>
        <input type="hidden" name="groep_title" value="<?php echo isset($_GET['groep_title']) ? htmlspecialchars($_GET['groep_title']) : ''; ?>">
        <button type="submit" class="btn btn-outline-dark btn-lg px-5" name="toevoegen">Toevoegen</button>
    </form>
</div>
