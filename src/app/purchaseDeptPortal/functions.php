<?php

if ($_SESSION['logged'] != true || $_SESSION['is_dept_manager'] != true) {
  $redirectlocation = APP_PATH . "/login/index.php";
  return header("location: {$redirectlocation}");
}


$sqlGetOrder = "
  select O.id, E.name empName, P.name prodName, P.price, O.amount, O.reason
  from _Order O
  inner join Employee E on E.id = O.employee_id
  inner join Product P on P.id = O.prod_id
  where '{$_SESSION['department']}' = E.department_name AND
  O.approved is null;";
  $sqlGetOrderResult = mysqli_query($conn, $sqlGetOrder);

 ?>
