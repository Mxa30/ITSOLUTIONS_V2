<?php
if ($_SESSION['logged'] != true || $_SESSION['is_dept_manager'] != true) {
  $redirectlocation = APP_PATH . "/login/index.php";
  return header("location: {$redirectlocation}");
}

// Get all order requests which are not approved yet
if ($_SESSION['department'] == "CEO") {
  $sqlGetOrder = "
    select O.id, E.name empName, P.name prodName, P.price, O.amount, O.reason
    from _Order O
    inner join Employee E on E.id = O.employee_id
    inner join Product P on P.id = O.prod_id
    where E.is_dept_manager = '1' AND
    O.approved is null;";
}else {
  $sqlGetOrder = "
  select O.id, E.name empName, P.name prodName, P.price, O.amount, O.reason
  from _Order O
  inner join Employee E on E.id = O.employee_id
  inner join Product P on P.id = O.prod_id
  where '{$_SESSION['department']}' = E.department_name AND
  E.id != '{$_SESSION['employee_id']}' AND
  O.approved is null;";
}
  $sqlGetOrderResult = mysqli_query($conn, $sqlGetOrder);

  function changeBudget($conn, $budget) {
    $sqlGetBudget="
    select budget, rest_budget
    from department
    where name = '{$_SESSION['department']}';";
    $sqlGetBudgetResult = mysqli_query($conn, $sqlGetBudget);

    while ($record = mysqli_fetch_assoc($sqlGetBudgetResult)) {
      $newRestBudget = $record['rest_budget'] + ($budget - $record['budget']);
      $sqlChangeBudget = "
      UPDATE `department`
      SET rest_budget = '{$newRestBudget}', budget = '{$budget}'
      WHERE name = '{$_SESSION['department']}';";

      if (mysqli_query($conn, $sqlChangeBudget)) { //IF QUERRY SUCCESFUL CONTINUE
        header("Refresh:0");
      }else {
        echo "Error: " . $sqlChangeBudget . "<br>" . mysqli_error($conn);
      }
    }
  }

  function acceptRequest($conn, $orderId, $totalPrice) {
    // CHECK IF THE PURCHASE IS JUSTIFIED BY THE BUDGET
    $sqlGetBudget="
    select budget, rest_budget
    from department
    where name = '{$_SESSION['department']}';";
    $sqlGetBudgetResult = mysqli_query($conn, $sqlGetBudget);

    while ($record = mysqli_fetch_assoc($sqlGetBudgetResult)) {
      if ($record['rest_budget']-$totalPrice >= 0) { //IF RESTEREND BUDGET - PRIJS GROTER IS OF GELIJK AAN 0 IS, VOER UIT
        // CHANGE BUDGET
        $newRestBudget = $record['rest_budget']-$totalPrice;
        $sqlChangeBudget = "
        UPDATE `department`
        SET rest_budget = '{$newRestBudget}'
        WHERE name = '{$_SESSION['department']}';";

        if (mysqli_query($conn, $sqlChangeBudget)) { //IF QUERRY SUCCESFUL CONTINUE
          $sqlGetSupplier = "
          SELECT S.name, S.email, P.name prodName, O.amount
          FROM _Order O
          INNER JOIN Product P on P.id = O.prod_id
          INNER JOIN Supplier S on S.name = P.supplier
          WHERE O.id = '{$orderId}';
          ";
          $sqlGetSupplierResult = mysqli_query($conn, $sqlGetSupplier);
          while ($record = mysqli_fetch_assoc($sqlGetSupplierResult)) {
            // SEND EMAIL
            $to_email = 'maxvelde_3010@hotmail.com';
            $subject = 'Order placement from IT Solutions';
            $headers = "Content-Type: text/html; charset='UTF-8'";
            $message = "
            Beste {$record['name']}, hierbij de volgende bestelling: <br>
            Ons OrderID: {$orderId}.<br>
            Product: {$record['prodName']}.<br>
            Aantal: {$record['amount']}.<br>
            De door ons berekende prijs: {$totalPrice}.
            ";
            mail($to_email,$subject,$message,$headers);
          }

          // SET APPROVED TO TRUE
          $sqlAcceptOrderQuery = "
          UPDATE `_order`
          SET approved = '1'
          WHERE `id` = '{$orderId}';";

          if (mysqli_query($conn, $sqlAcceptOrderQuery)) {
            //UPDATES
            header("Refresh:0");
          }else{
            echo "Error: " . $sqlAcceptOrderQuery . "<br>" . mysqli_error($conn);
          }
        }else {
          header("Refresh:0");
        }
      }else {
        header("Refresh:0");
      }
    }
  }

  function denyRequest($conn, $orderId, $reason) {

    $sqlAcceptOrderQuery = "
    UPDATE `_order`
    SET approved = '0', deny_reason = '" . $reason . "'
    WHERE `id` = {$orderId};";

    if (mysqli_query($conn, $sqlAcceptOrderQuery)) {
      //UPDATES
      header("Refresh:0");
    }else{
      echo "Error: " . $sqlAcceptOrderQuery . "<br>" . mysqli_error($conn);
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['budgetPriceButton'])) {
      changeBudget($conn, $_POST['budgetPrice']);
    }
    while($record = mysqli_fetch_assoc($sqlGetOrderResult)){
      if (isset($_POST['accept' . $record['id']])) {
        // Call acceptRequest function with the parameters form the according request
        $totalPrice = $record['price']*$record['amount'];
        acceptRequest($conn, $record['id'], $totalPrice);
      }
      if (isset($_POST['deny' . $record['id']])) {

        if ($_POST['reason' . $record['id']] == 1) {
          $reason = "Recent al gekocht";
          // Call acceptRequest function with the parameters form the according request
          denyRequest($conn, $record['id'],$reason);
        }elseif ($_POST['reason' . $record['id']] == 2) {
          $reason = "Overbodig artikel";
          // Call acceptRequest function with the parameters form the according request
          denyRequest($conn, $record['id'],$reason);
        }elseif ($_POST['reason' . $record['id']] == 3) {
          if (empty($_POST['budgetReason' . $record['id']])) {
            header("Refresh:0");
          }else {
            $reason = $_POST['budgetReason' . $record['id']];
            // Call acceptRequest function with the parameters form the according request
            denyRequest($conn, $record['id'],$reason);
          }
        }elseif ($_POST['reason' . $record['id']] == 4) {
          $reason = "Niet meer leverbaar";
          // Call acceptRequest function with the parameters form the according request
          denyRequest($conn, $record['id'],$reason);
        }elseif ($_POST['reason' . $record['id']] == 5) {
          $reason = "Externe omstandigheden";
          // Call acceptRequest function with the parameters form the according request
          denyRequest($conn, $record['id'],$reason);
        }else {
          header("Refresh:0");
        }
      }
    }
  }
?>
