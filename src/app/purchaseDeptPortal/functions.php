<?php

if ($_SESSION['logged'] != true || $_SESSION['department'] != "Purchasing") {
  $redirectlocation = APP_PATH . "/login/index.php";
  return header("location: {$redirectlocation}");
}

  $sqlGetSuppliers = "
  SELECT name
  from supplier
  order by name;
  ";
  $sqlGetSuppliersResult = mysqli_query($conn, $sqlGetSuppliers);

  $getInkoopOrder = "
  select id, prod_naam, prod_omschrijving, reden, employee_id, approved, added
  from Inkoop
  WHERE approved = 1 AND
  added is null
  order by id desc;";
  $sqlGetInkoopOrder = mysqli_query($conn, $getInkoopOrder);

  $getOrder = "
  select O.id, O.approved, O.picked_up, O.delivered, O.prod_id, O.amount, O.paid, P.price, P.name prodName, P.supplier
  from _Order O, Product P
  where approved = 1 and
  O.prod_id = P.id
  order by id desc;";
  $sqlGetFinanceOrderResult = mysqli_query($conn, $getOrder);

  // $sqlGetOrder = "
  // select O.id, E.name empName, P.name prodName, P.price, O.amount, O.reason
  // from _Order O
  // inner join Employee E on E.id = O.employee_id
  // inner join Product P on P.id = O.prod_id
  // where '{$_SESSION['department']}' = E.department_name AND
  // O.approved is null;";
  // $sqlGetOrderResult = mysqli_query($conn, $sqlGetOrder);

  function searchOrder($id){
    $idVal = "and O.id = '{$id}'";
    $getOrder = "
    select O.id, O.approved, O.picked_up, O.delivered, O.prod_id, O.amount, O.paid, P.price, P.name prodName
    from _Order O, Product P
    where approved = 1 and
    O.prod_id = P.id {$idVal}
    order by id desc;";
    return $getOrder;
  }

  function addProduct($conn, $orderId, $record) {
    if ($record['prodSupplier'] == "0") {
      $sqlQuery = "
      INSERT INTO `supplier` (`name`, `email`)
      VALUES ('{$record['supplierName']}', '{$record['supplierEmail']}');
      ";
      if (mysqli_query($conn, $sqlQuery)) {
        //ADDED
        $sqlQuery = "
        INSERT INTO `product` (`name`, `price`, `supplier`)
        VALUES ('{$record['prodName']}', '{$record['prodPrice']}', '{$record['supplierName']}');
        ";
        if (mysqli_query($conn, $sqlQuery)) {
          $sqlGetSupplier = "
          SELECT S.name, S.email, P.name prodName, p.id prodId,  O.amount
          FROM _Order O
          INNER JOIN Product P on P.id = O.prod_id
          INNER JOIN Supplier S on S.name = P.supplier
          WHERE O.id = '{$orderId}';
          ";
          $sqlGetSupplierResult = mysqli_query($conn, $sqlGetSupplier);
          // SEND MAIL TO REQUESTING EMPLOYEE
          $sqlGetEmployee = "
          SELECT E.name, E.email
          FROM Employee E
          WHERE E.id = '{$_SESSION['employee_id']}';
          ";
          $sqlGetEmployeeResult = mysqli_query($conn, $sqlGetEmployee);
          // SEND MAIL
          while ($record = mysqli_fetch_assoc($sqlGetEmployeeResult)) {
            // SEND EMAIL
            $to_email = 'maxvelde_3010@hotmail.com';
            $subject = 'Product toegevoegd from IT Solutions';
            $headers =  'MIME-Version: 1.0' . "\r\n";
            $headers .= 'From: IT Solutions Ordering System <codrrnl@gmail.com>' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $message = "
            <p>Beste {$record['name']},</p>
            <p>Er is een nieuw product voor jou toegevoegd in het IT Solutions koopportaal</p>
            <p>Klik <a href='http://localhost/IT_Solutions_BPM_v2/src/app/buyportal/index.php'>hier</a> om het product te bestellen</p>
            ";
            if (mail($to_email,$subject,$message,$headers)) { //IF MAIL IS SENT SUCCESFULLY
              $sqlDeliverOrderQuery = "
              UPDATE `inkoop`
              SET added = '1'
              WHERE `id` = '{$orderId}';";

              if (mysqli_query($conn, $sqlDeliverOrderQuery)) {
                //UPDATES
                header("Refresh:0");
              }else{
                echo "Error: " . $sqlDeliverOrderQuery . "<br>" . mysqli_error($conn);
              }
            }else{
              echo "Error: " . $sqlDeliverOrderQuery . "<br>" . mysqli_error($conn);
            }
          }
        }else{
          echo "Error: " . $sqlQuery . "<br>" . mysqli_error($conn);
        }
      }else{
        echo "Error: " . $sqlQuery . "<br>" . mysqli_error($conn);
      }
    }else {


      $sqlQuery = "
      INSERT INTO `product` (`name`, `price`, `supplier`)
      VALUES ('{$record['prodName']}', '{$record['prodPrice']}', '{$record['prodSupplier']}');
      ";
      if (mysqli_query($conn, $sqlQuery)) {
        $sqlDeliverOrderQuery = "
        UPDATE `inkoop`
        SET added = '1'
        WHERE `id` = '{$orderId}';";

        if (mysqli_query($conn, $sqlDeliverOrderQuery)) {
          // SEND MAIL TO REQUESTING EMPLOYEE
          $sqlGetEmployee = "
          SELECT E.name, E.email
          FROM Employee E
          WHERE E.id = '{$_SESSION['employee_id']}';
          ";
          $sqlGetEmployeeResult = mysqli_query($conn, $sqlGetEmployee);
          // SEND MAIL
          while ($record = mysqli_fetch_assoc($sqlGetEmployeeResult)) {
            // SEND EMAIL
            $to_email = 'maxvelde_3010@hotmail.com';
            $subject = 'Product toegevoegd from IT Solutions';
            $headers =  'MIME-Version: 1.0' . "\r\n";
            $headers .= 'From: IT Solutions Ordering System <codrrnl@gmail.com>' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $message = "
            <p>Beste {$record['name']},</p>
            <p>Er is een nieuw product voor jou toegevoegd in het IT Solutions koopportaal</p>
            <p>Klik <a href='http://localhost/IT_Solutions_BPM_v2/src/app/buyportal/index.php'>hier</a> om het product te bestellen</p>
            ";
            if (mail($to_email,$subject,$message,$headers)) { //IF MAIL IS SENT SUCCESFULLY
              header("Refresh:0");
            }else {
              header("Refresh:0");
            }
          }
        }else{
          echo "Error: " . $sqlDeliverOrderQuery . "<br>" . mysqli_error($conn);
        }
      }else{
        echo "Error: " . $sqlQuery . "<br>" . mysqli_error($conn);
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    while($record = mysqli_fetch_assoc($sqlGetInkoopOrder)){
      if (isset($_POST['submitNewProd' . $record['id']])) {
        if ($_POST['prodSupplier'] == "0") {
          if (!empty($_POST['supplierName']) && !empty($_POST['supplierEmail'])) {
            $arr = ["prodName"=>$_POST['prodName'],
                    "prodPrice"=>$_POST['prodPrice'],
                    "prodSupplier"=>$_POST['prodSupplier'],
                    "supplierName"=>$_POST['supplierName'],
                    "supplierEmail"=>$_POST['supplierEmail']];
            addProduct($conn, $record['id'], $arr);
          }else {
            header("Refresh:0");
          }
        }else {
          $arr = ["prodName"=>$_POST['prodName'],
                  "prodPrice"=>$_POST['prodPrice'],
                  "prodSupplier"=>$_POST['prodSupplier']];
          addProduct($conn, $record['id'], $arr);
        }
      }
    }
    if (isset($_POST['submitResId'])) {
      if (!empty($_POST['rearchOrderId']) || trim($_POST['rearchOrderId']) != "") {
        $sqlGetFinanceOrderResult = mysqli_query($conn, searchOrder($_POST['rearchOrderId']));
      }else {
        header("Refresh:0");
      }
    }
  }


 ?>
