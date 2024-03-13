<?php
session_start(); 
include '../private/conn.php';

function calculateAge($birthdate) {
    $today = new DateTime();
    $diff = $today->diff(new DateTime($birthdate));
    return $diff->y;
}

$emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
$postcodeRegexNL = '/^\d{4}\s?[a-zA-Z]{2}$/';

if (isset($_POST['signup'])) {
    $voornaam = $_POST['firstName'];
    $achternaam = $_POST['lastName'];
    $woonplaats = $_POST['city'];
    $straat = $_POST['street'];
    $huisnummer = $_POST['houseNumber'];    
    $postcode = $_POST['postalCode'];
    $email = $_POST['email'];
    $gebruiker_naam = $_POST['username'];
    $wachtwoord = $_POST['password'];
    $geboortedatum = $_POST['birthdate'];

    if (!preg_match($postcodeRegexNL, $postcode)) {
        $_SESSION['melding'] = 'Ongeldige postcode.';
        header('location:../index.php?page=sign_up');
        exit;
    }
    
    if (!preg_match($emailRegex, $email)) {
        $_SESSION['melding'] = 'Ongeldig e-mailadres.';
        header('location:../index.php?page=sign_up');
        exit;
    }

    $leeftijd = calculateAge($geboortedatum);
    if ($leeftijd < 18) {
        $_SESSION['melding'] = 'Je moet minstens 18 jaar oud zijn om een account aan te maken.';
        header('location:../index.php?page=sign_up');
        exit;
    }
    
    $sql = 'INSERT INTO users (voornaam, achternaam, Woonplaats, Straat, huisnummer, postcode, email, gebruikernaam, wachtwoord, geboortedatum) VALUES (:firstName, :lastName, :city, :street, :houseNumber, :postalCode, :email, :username, :password, :birthdate)';
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':firstName', $voornaam);
    $sth->bindParam(':lastName', $achternaam);
    $sth->bindParam(':city', $woonplaats);
    $sth->bindParam(':street', $straat);
    $sth->bindParam(':houseNumber', $huisnummer);
    $sth->bindParam(':postalCode', $postcode);
    $sth->bindParam(':email', $email);
    $sth->bindParam(':username', $gebruiker_naam);
    $sth->bindParam(':password', $wachtwoord);
    $sth->bindParam(':birthdate', $geboortedatum);

    if ($sth->execute()) {
        $_SESSION['melding'] = 'Registratie succesvol! U kunt nu inloggen.';
        header('location:../index.php?page=Home');
        exit;
    } else {
        $_SESSION['melding'] = 'Registratie mislukt. Probeer het opnieuw.';
        header('location:../index.php?page=sign_up');
        exit;
    }
}
?>
