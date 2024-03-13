<?php
if (isset($_SESSION['melding'])) {
    echo '<p style="color: red;">' . $_SESSION['melding'] . '</p>';
    unset($_SESSION['melding']);
  }



?>
<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <form action="php/signup.php" method="POST">
                <div class="mb-md-5 mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">Registreren</h2>
                <p class="text-white-50 mb-5">Graag volgende informatie invullen!</p>

                <input type="text" id="firstName" name="firstName" class="form-control form-control-lg" />
                  <label class="form-label" for="firstName">Voornaam</label>

                  <input type="text" id="lastName" name="lastName" class="form-control form-control-lg" />
                  <label class="form-label" for="lastName">Achternaam</label>

                  <input type="text" id="city" name="city" class="form-control form-control-lg" />
                  <label class="form-label" for="city">Woonplaats</label>

                  <input type="text" id="street" name="street" class="form-control form-control-lg" />
                  <label class="form-label" for="street">Straat</label>

                  <input type="text" id="houseNumber" name="houseNumber" class="form-control form-control-lg" />
                  <label class="form-label" for="houseNumber">Huisnummer</label>

                  <input type="text" id="postalCode" name="postalCode" class="form-control form-control-lg" />
                  <label class="form-label" for="postalCode">Postcode</label>

                  <input type="email" id="email" name="email" class="form-control form-control-lg" />
                  <label class="form-label" for="email">Email</label>

                  <input type="username" id="username" name="username" class="form-control form-control-lg" />
                  <label class="form-label" for="username">gebruikersnaam</label>

                  <input type="password" id="password" name="password" class="form-control form-control-lg" />
                  <label class="form-label" for="password">Wachtwoord</label>

                  <input type="password" id="again_password" name="password_again" class="form-control form-control-lg" />
                  <label class="form-label" for="password">herhaal wachtwoord</label>


                  <input type="date" id="birthdate" name="birthdate" class="form-control form-control-lg" />
                  <label class="form-label" for="birthdate">Geboortedatum</label>


                <button class="btn btn-outline-light btn-lg px-5" name="signup" type="submit">Registreren</button>

                

                </div>
            </form>

            <div>
              <p class="mb-0">Heb je al een account? <a href="index.php?page=Home" class="text-white-50 fw-bold">Log in</a>
              </p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  const password = document.getElementById('password');
  const passwordRepeat = document.getElementById('again_password');

  document.querySelector('form').addEventListener('submit', function(event) {
    if (password.value !== passwordRepeat.value) {
      event.preventDefault();
      alert('Wachtwoord en wachtwoordherhaling komen niet overeen!');
    }
  });
</script>