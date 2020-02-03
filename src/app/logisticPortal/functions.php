<?php

if ($_SESSION['logged'] != true || $_SESSION['department'] != "Logistics") {
  $redirectlocation = APP_PATH . "/login/index.php";
  return header("location: {$redirectlocation}");
}

$sqlGetLogistics = "
  select O.id, E.name empName, E.department_name, P.name prodName, O.amount, P.id prodID
  from _Order O
  inner join Employee E on E.id = O.employee_id
  inner join Product P on P.id = O.prod_id
  where O.approved = '1' AND
        O.delivered is null
        order by id desc;";
  $sqlGetLogisticsResult = mysqli_query($conn, $sqlGetLogistics);

  function searchOrder($id){
    $idVal = "and O.id = '{$id}'";
    $sqlGetLogistics = "
      select O.id, E.name empName, E.department_name, P.name prodName, O.amount, P.id prodID
      from _Order O
      inner join Employee E on E.id = O.employee_id
      inner join Product P on P.id = O.prod_id
      where O.approved = '1' AND
            O.prod_id = P.id {$idVal} AND
            O.delivered is null
            order by id desc;";
    return $sqlGetLogistics;
  }

  function deliverRequest($conn, $orderId) {
    $sqlDeliverOrderQuery = "
    UPDATE `_order`
    SET delivered = '1'
    WHERE `id` = {$orderId};";

    if (mysqli_query($conn, $sqlDeliverOrderQuery)) {
      //UPDATES
      header("Refresh:0");
    }else{
      echo "Error: " . $sqlDeliverOrderQuery . "<br>" . mysqli_error($conn);
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    while($record = mysqli_fetch_assoc($sqlGetLogisticsResult)){
      if (isset($_POST['deliver' . $record['id']])) {
        // Call acceptRequest function with the parameters form the according request
        deliverRequest($conn, $record['id']);
      }
    }
    if (isset($_POST['submitResId'])) {
      if (!empty($_POST['rearchOrderId']) || trim($_POST['rearchOrderId']) != "") {
        $sqlGetLogisticsResult = mysqli_query($conn, searchOrder($_POST['rearchOrderId']));
      }else {
        header("Refresh:0");
      }
    }
  }
?>
