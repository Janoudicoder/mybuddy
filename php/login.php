<?php
session_start();

require '../private/conn.php';

if (isset($_POST['gebruiker_naam']) && isset($_POST['password'])) {
    $username = $_POST['gebruiker_naam'];
    $password = $_POST['password'];

    $sql = 'SELECT id, Wachtwoord, usertype FROM users WHERE gebruiker_naam = :gebruiker_naam';
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':gebruiker_naam' => $username));

    $matchingUsers = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (count($matchingUsers) > 0) {
        foreach ($matchingUsers as $rsUser) {
            if ($password == $rsUser['Wachtwoord']) {
                $_SESSION['user_id'] = $rsUser['id']; 

                if (isset($rsUser['usertype'])) {
                    if ($rsUser['usertype'] == 'admin') {
                        $_SESSION['user'] = 'admin'; 

                        header('location: ../index.php?page=Admin_shop');
                        exit();
                    } else if ($rsUser['usertype'] == 'user') {
                        $_SESSION['user'] = 'gebruiker';
                        header('location: ../index.php?page=shop');
                        exit();
                    }
                } else {
                    $_SESSION['medling'] = 'User type not found';
                    header('location: ../index.php?page=login');
                    exit();
                }
            }
        }
        
        $_SESSION['medling'] = 'Wrong password';
        header('location: ../index.php?page=login');
        exit();
    } else {
        $_SESSION['medling'] = 'Incorrect username';
        header('location: ../index.php?page=login');
        exit();
    }
} else {
    $_SESSION['medling'] = 'Username and password are required.';
    header('location: ../index.php?page=login');
    exit();
}
?>
