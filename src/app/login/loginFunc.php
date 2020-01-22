<?php
// Login functie
function loginFunc($email, $pass, $conn) {
  $sqlLoginQ = "
  select id, name, department_name, is_dept_manager, email, password
  from Employee
  where email = '{$email}' and password = '{$pass}'";
  $sqlLoginResult = mysqli_query($conn, $sqlLoginQ);
  while ($record = mysqli_fetch_assoc($sqlLoginResult)) {
    if (filter_var($email,FILTER_SANITIZE_EMAIL)==true || filter_var($pass,FILTER_SANITIZE_STRING)==true && $record['email'] == $email && $record['password'] == $pass) {
      if ($record['is_dept_manager'] == true) {
        // Setting session variables for department manager
        $_SESSION['logged'] = true;
        $_SESSION['is_dept_manager'] = true;
        $_SESSION['employee_id'] = $record['id'];
        $_SESSION['name'] = $record['name'];
        $_SESSION['department'] = $record['department_name'];
        $redirectlocation = APP_PATH . "/depManPortal/index.php";
      }else {
        // Setting session variables for other users
        $_SESSION['logged'] = true;
        $_SESSION['is_dept_manager'] = 0;
        $_SESSION['employee_id'] = $record['id'];
        $_SESSION['name'] = $record['name'];
        $_SESSION['department'] = $record['department_name'];
        $redirectlocation = APP_PATH . "/buyPortal/index.php";
      }
      header("location: {$redirectlocation}");
  		exit;
    }
  }

  // Als de while loop niets kan vinden.
  $_SESSION['logged'] = 0;
  $_SESSION['is_dept_manager'] = 0;
  $redirectlocation = APP_PATH . "/login/index.php";
  return header("location: {$redirectlocation}");
}

// function aanmeldFunc($email, $pass, $conn) {
//   if (filter_var($email,FILTER_SANITIZE_EMAIL)==true || filter_var($pass,FILTER_SANITIZE_STRING)==true){
//   $sqlLoginQ = "
//   select email
//   from login
//   where email = '{$email}'";
//   $sqlLoginResult = mysqli_query($conn, $sqlLoginQ);
//   // Checken of het email an bestaat in de database
//   while ($record = mysqli_fetch_assoc($sqlLoginResult)) {
//     // Als deze bestaat, geef dan een error dat het email al bestaat.
//     if ($record['email'] == $email) {
//       return "Dat email bestaat al, log in op de login-pagina.";
//       header("location: {$redirectlocation}");
//   		exit;
//     }
//   }
//   // Als de while loop niets kan vinden.
//   $sqlNewAccountQ = "
//   insert into login (email, password)
//   values ('{$email}','{$pass}')";
//   if (mysqli_query($conn, $sqlNewAccountQ)) {
//     // Added succesfully.
//     $_SESSION['logged'] = true;
//     $_SESSION['admin'] = false;
//     $_SESSION['email'] = $email;
//
//     $redirectlocation = PAGE_PATH . "/kamer_overzicht/html/index.php";
//     header("location: {$redirectlocation}");
//     exit;
//   }
//   else{
//     echo "Error: " . $sqlNewAccountQ . "<br>" . mysqli_error($conn);
//   }
// }
// }

// Als er een request word gedaan naar de server door middel van POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Als de POST request van de knop 'loginButton' komt
  if (isset($_POST['loginButton'])) {
    // Als ze niet zijn ingevuld of leeg zijn, return dan
    if (!isset($_POST['email']) || !isset($_POST['password']) || empty($_POST['email']) || empty($_POST['password'])) {
      return;
    }
    else {
      $email = $_POST['email'];
      $pass = $_POST['password'];
      $errorCode = loginFunc($email,$pass,$conn);
    }
  }
  // Als de POST request van de knop 'aanmeldButton' komt

  // elseif (isset($_POST['aanmeldButton'])) {
  //   // Als ze niet zijn ingevuld of leeg zijn, return dan
  //   if (!isset($_POST['email']) || !isset($_POST['password']) || empty($_POST['email']) || empty($_POST['password'])) {
  //     return;
  //   }
  //   else {
  //     $email = $_POST['email'];
  //     $pass = $_POST['password'];
  //     $errorCodeA = aanmeldFunc($email,$pass,$conn);
  //   }
  // }
}
 ?>
