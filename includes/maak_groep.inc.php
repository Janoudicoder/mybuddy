<body>
    <div class="container">
        <h1>Nieuwe groep maken</h1>
        <form action="php/maak_groep.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="groep_plaatje" class="form-label">Afbeelding:</label>
                <input type="file" class="form-control" id="groep_plaatje" name="groep_plaatje">
            </div>
            <div class="mb-3">
                <label for="groep_title" class="form-label">Titel:</label>
                <input type="text" class="form-control" id="groep_title" name="groep_title">
            </div>
            <div class="mb-3">
                <label for="groep_doel" class="form-label">Doel:</label>
                <textarea class="form-control" id="groep_doel" name="groep_doel" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-outline-dark btn-lg px-5">Groep toevoegen</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>