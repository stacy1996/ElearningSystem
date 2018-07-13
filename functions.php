<?php include('db.php')
?>
<?php
session_start();

// connect to database
/* Database connection settings */
////$host = 'localhost';
////$username = 'wairegi';
////$pass = 'Stacy1996';
////$db = 'elearningsystem';
////$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
//$db = mysqli_connect('localhost', 'root', '', 'elearningsystem');
////$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

// variable declaration
//$userId = "";
$username= "";
$userType="";
$email    = "";
$password = "";
$errors   = array();

// call the register() function if register is clicked
if (isset($_POST['register'])) {
    register();
}
if (isset($_POST['pregnancy'])) {
    pregnancy();
}
//if (isset($_POST['Book'])){
//    Book();
//}

//function Book(){
//    global $db, $errors, $id, $subject;
//
//    // receive all input values from the form. Call the e() function
//    // defined below to escape form values
//    $id =  e($_POST['id']);
//    $f_name =  e($_POST['f_name']);
//    $age  =  e($_POST['age']);
//    $gender  =  e($_POST['gender']);
//    $subject  =  e($_POST['subject']);
//    $date  =  e($_POST['date']);
//    $time  =  e($_POST['time']);
//
//    // form validation: ensure that the form is correctly filled
//    if (empty($id)) {
//        array_push($errors, "Id or Passport is required");
//    }
//    if (empty($f_name)) {
//        array_push($errors, "Full names are required");
//    }
//    if (empty($age)) {
//        array_push($errors, "Age group is required");
//    }
//    if (empty($gender)) {
//        array_push($errors, "Gender is required");
//    }
//    if (empty($subject)) {
//        array_push($errors, "Description is required");
//    }
//    if (empty($date)) {
//        array_push($errors, "Date is required");
//    }
//    if (empty($time)) {
//        array_push($errors, "Time preferred is required");
//    }
//
//    if (count($errors) == 0)
//    {
//        $query = "INSERT INTO appointments (id_number, full_names, age, gender,description, app_date, app_time)
//                  VALUES ('$id', '$f_name','$age','$gender','$subject','$date','$time')";
//        mysqli_query($db, $query);
//        $_SESSION['success'] = "Appointment successfully made.";
//        header('location: index.php');
//
//
//    }
//
//}

// REGISTER USER
function register()
{
    // call these variables with the global keyword to make them available in function
    global $connection, $errors, $email, $password, $userType;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    //$userType    =  e($_POST['userType']);
    //$userId      =  e($_POST['userId']);
//

//    $username = stripslashes($_REQUEST['username']);
//    $username = mysqli_real_escape_string($connection,$username);
//    $email = stripslashes($_REQUEST['email']);
//    $email = mysqli_real_escape_string($connection,$email);
//    $password = stripslashes($_REQUEST['password']);
//    $password = mysqli_real_escape_string($connection,$password);
    if (isset($_REQUEST['username'])) {
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($connection, $username);
        $email = stripslashes($_REQUEST['email']);
        $email = mysqli_real_escape_string($connection, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($connection, $password);

        $userType = mysqli_real_escape_string($connection, $userType);


        $query = "INSERT into `account` (userName, password, email, userType)
        VALUES ('$username', '" . md5($password) . "', '$email', '$userType')";
        $result = mysqli_query($connection, $query);
        if ($result) {
            header("login.php");
        } else {
            echo 'Could not register';
        }
    }


    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }


    // register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password);//encrypt the password before saving in the database
        $_SESSION['success'] = "New user successfully created!!";
        if (isset($_POST['userType'])) {


            $username = stripslashes($_REQUEST['username']);
            $username = mysqli_real_escape_string($connection, $username);
            $email = stripslashes($_REQUEST['email']);
            $email = mysqli_real_escape_string($connection, $email);
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($connection, $password);
            $userType = stripslashes($_REQUEST['userType']);
            $userType = mysqli_real_escape_string($connection, $userType);

            $query = "INSERT INTO account (userId,userName,userType,email,password) 
                 VALUES('$username','$userType','$email','$password')";
            mysqli_query($connection, $query);
            $_SESSION['success'] = "New user successfully created!!";
            header('location: login.php');
        } else {
            $username = stripslashes($_REQUEST['username']);
            $username = mysqli_real_escape_string($connection, $username);
            $query = "INSERT INTO account (userId,userName,userType,email, password) 
                 VALUES('$username', '$email', 'user', '$password')";
            mysqli_query($connection, $query);

            // get id of the created user
            $logged_in_userId = mysqli_insert_id($connection);

            $_SESSION['user'] = getUserById($logged_in_userId); // put logged in user in session
            $_SESSION['success'] = "You are now logged in";
            header('location: login.php');
        }
    }
}


// return user array from their id
function getUserById($userId){
    global $connection;
    $query = "SELECT * FROM account WHERE id=" . $userId;
    $result = mysqli_query($connection, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}

//escape string
function e($val){
    global $connection;
    return mysqli_real_escape_string($connection, trim($val));
}

function display_error() {
    global $errors;
    if (count($errors) > 0){
        echo '<div class="error">';
        foreach ($errors as $error){
            echo $error .'<br>';
        }
        echo '</div>';
    }
}
function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    }else{
        return false;
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}
if (isset($_POST['login'])) {
    login();
}

// LOGIN USER
function login()
{
    global $connection, $email, $errors;

    // grap form values
    $email = e($_POST['email']);
    $password = e($_POST['password']);

    // make sure form is filled properly
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // attempt login if no errors on form
    if (count($errors) == 0) {
        $password = md5($password);

        $query = "SELECT * FROM account WHERE email='$email' AND password='$password' LIMIT1";
        $result = mysqli_query($connection, $query);
        if ($result) {
            header('location: homepage.php');
        } else {
            array_push($errors,'Wrong email/password');
        }
        // User login process, checks if user exists and password is correct

// Escape email to protect against SQL injections
//$email = $mysqli->escape_string($_POST['email']);
//$result = $mysqli->query("SELECT * FROM users WHERE email='$email'");
//
//if ( $result->num_rows == 0 ){ // User doesn't exist
//   $_SESSION['message'] = "User with that email doesn't exist!";
//    header("location: error.php");
//}
//else { // User exists
//    $email = $result->fetch_assoc();
//}
//    if ( password_verify($_POST['password'], $['password']) ) {
//
//       $_SESSION['userId'] = $user[''];
//        $_SESSION['userName'] = $user['first_name'];
//       $_SESSION['password'] = $user['password'];
//       $_SESSION['email']=$user['email'];
//       $_SESSION['active'] = $user['active'];
//
//       // This is how we'll know the user is logged in
//        $_SESSION['logged_in'] = true;
//
//        header("location: profile.php");
//}
//    else {
//       $_SESSION['message'] = "You have entered wrong password, try again!";
//       header("location: error.php");
//   }
//}
//        //$results = mysqli_query($connection, $query);
//
//        //if (mysqli_num_rows($result) == 1) { // user found
//        // check if user is admin or user
//        //$logged_in_user = mysqli_fetch_assoc($result);
//        //if ($logged_in_user['userType'] == 'admin') {
//
//        // $_SESSION['user'] = $logged_in_user;
//        //$_SESSION['success']  = "You are now logged in";
//       // header('location: homepage.php');
//        //}else{
//        //$_SESSION['user'] = $logged_in_user;
//        // $_SESSION['success']  = "You are now logged in";
//
//        // header('location: homepage.php');
//        // }
//        //}else {
//        //array_push($errors, "Wrong email/password!!");
//        //}
    }
}

function isAdmin()
{
    if (isset($_SESSION['user']) && $_SESSION['user']['userType'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

function pregnancy()
{
    global $connection, $dueDate, $pregnancyId, $userName, $birthday, $startDate;
    if (isset($_REQUEST['dueDate'])) {
        $pregnancyId = stripslashes($_REQUEST['pregnancyId']);
        $pregnancyId = mysqli_real_escape_string($connection, $pregnancyId);
        $userName = stripslashes($_REQUEST['userName']);
        $userName = mysqli_real_escape_string($connection, $userName);
        $birthday = stripslashes($_REQUEST['birthday']);
        $birthday = mysqli_real_escape_string($connection, $birthday);
        $startDate = stripslashes($_REQUEST['startDate']);
        $startDate = mysqli_real_escape_string($connection, $startDate);
        $dueDate = stripslashes($_REQUEST['dueDate']);
        $dueDate = mysqli_real_escape_string($connection, $dueDate);


        $query = "INSERT into `pregnancydetails` (pregnancyId, userName, birthday, startDate, dueDate)
        VALUES ('$pregnancyId', '$userName', '$birthday', '$startDate', $dueDate)";
        $result = mysqli_query($connection, $query);
        if ($result) {
            header("pregnancyModule.php");
        } else {
            echo 'Could not register';
        }
    }
}
