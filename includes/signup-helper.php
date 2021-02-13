<?php

if(isset($_POST['signup-submit'])){

    require 'dbhandler.php';

    $username = $_POST['uname'];
    $email = $_POST['email'];
    $passw = $_POST['pwd'];
    $passw_rep = $_POST['con-pwd'];
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];

    if($passw !== $passw_rep){
        header("Location: ../singup.php?error=diffPasswords");
        exit();
    }
 
    else{
        $sql = "SELECT uname FROM users WHERE uname=?";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../signup.php?error=SQLInjection");
            exit();

        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $check = mysqli_stmt_num_rows($stmt);

            if($check > 0){
                header("Location: ../signup.php?error=UsernameTaken");
                exit();
            }
            else{
                $sql = "INSERT INTO users (Lname, Fname, email, uname, password) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                 if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../signup.php?error=SQLInjection");
                        exit();
                 }
                 else {
                     $hashed = password_hash($passw, PASSWORD_BCRYPT);
                     mysqli_stmt_bind_param($stmt, "sssss", $Lname, $Fname, $email, $username, $hashed);
                     mysqli_stmt_execute($stmt);
                     mysqli_stmt_store_result($stmt);

                     $sqlImg = "INSERT INTO profiles (uname, Fname) VALUES ('$username', '$Fname')";
                     $stmt = mysqli_stmt_init($conn);
                     mysqli_stmt_bind_param($stmt, "ss",$username, $Fname);
                     mysqli_stmt_execute($stmt);
                     mysqli_stmt_store_result($stmt);

                     header("Location: ../signup.php?signup=succes");
                exit();
                 }
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }

 }
 else {
    header("Location: ../signup.php");
    exit();
 }