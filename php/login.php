<?php
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {
require '../private/conn.php';
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = 'SELECT id, Wachtwoord, usertype FROM users WHERE email = :email';
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':email' => $email));

    $matchingUsers = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (count($matchingUsers) > 0) {
        foreach ($matchingUsers as $rsUser) {
            if ($password == $rsUser['Wachtwoord']) {
                $_SESSION['id'] = $rsUser['id']; 

                if (isset($rsUser['usertype'])) {
                    if ($rsUser['usertype'] == 'admin') {
                        $_SESSION['user'] = 'Admin'; 

                        header('location: ../index.php?page=Admin_shop');
                        exit();
                    } else if ($rsUser['usertype'] == 'user') {
                        $_SESSION['user'] = 'gebruiker';
                        header('location: ../index.php?page=Groepen');
                        exit();
                    }
                } else {
                    $_SESSION['melding'] = 'User type not found';
                    header('location: ../index.php?page=Home');
                    exit();
                }
            }
        }

        $_SESSION['melding'] = 'Wrong password';
        header('location: ../index.php?page=Home');
        exit();
    } else {
        $_SESSION['melding'] = 'Incorrect username';
        header('location: ../index.php?page=Home');
        exit();
    }
} else {
    $_SESSION['melding'] = 'Username and password are required.';
    header('location: ../index.php?page=Home');
    exit();
}
?>  
