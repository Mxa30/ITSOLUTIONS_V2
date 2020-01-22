<?php

$sqlGetLogistics = "
  select O.id, E.name empName, E.department_name, P.name prodName, P.price, O.amount, O.reason
  from _Order O
  inner join Employee E on E.id = O.employee_id
  inner join Product P on P.id = O.prod_id
  where '{$_SESSION['department']}' = E.department_name AND
  O.approved is null;";
  $sqlGetLogisticsResult = mysqli_query($conn, $sqlGetLogistics);

?>
