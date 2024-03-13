<?php

if (isset($_SESSION['id'])) {
    $displayLoginButton = false;
} else {
    $displayLoginButton = true;
}
if (isset($_SESSION['melding'])) {
    echo '<p style="color: red;">' . $_SESSION['melding'] . '</p>';
    unset($_SESSION['melding']);
  }

?>
<div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="my-3 p-3">
        <h2 class="display-5">MBetaal</h2>
        <p class="lead">Handel gelijkmatig</p>
    </div>
    
   
      <div class="bg-dark shadow-sm mx-auto" style="width: 80%; border-radius: 21px 21px 0 0;">
         <div class="col-md-6 p-5">
            <div class="form-popup" id="myForm">
                <form action="php/login.php" method="post" class="bg-light p-4 rounded">
                    <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                    <p class="text-dark-50 mb-5">Voer uw login en wachtwoord in!</p>
      


                    <div class="form-outline form-white mb-4">
                        <input type="email" name="email" id="typeEmailX" class="form-control form-control-lg" />
                        <label class="form-label" for="typeEmailX">Email</label>
                    </div>

                    <div class="form-outline form-white mb-4">
                        <input type="password" name="password" id="typePasswordX" class="form-control form-control-lg" />
                        <label class="form-label" for="typePasswordX">Wachtwoord</label>
                    </div>
                    

   <?php if ($displayLoginButton): ?>
                 <button class="open-button" onclick="openForm()">Login</button>
                 


                <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
            </div>
                        


            <script>
            function openForm() {
            document.getElementById("myForm").style.display = "block";
            }

            function closeForm() {
            document.getElementById("myForm").style.display = "none";
            }
            </script>

                
                </form>
            </div>
         </div>
</div>
<?php endif; ?>



<button class="open-button" onclick="openForm()">Login</button>


<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>