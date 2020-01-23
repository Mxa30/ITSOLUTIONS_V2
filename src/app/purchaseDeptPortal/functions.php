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
  WHERE added is null
  order by id desc;";
  $sqlGetInkoopOrder = mysqli_query($conn, $getInkoopOrder);

  // $sqlGetOrder = "
  // select O.id, E.name empName, P.name prodName, P.price, O.amount, O.reason
  // from _Order O
  // inner join Employee E on E.id = O.employee_id
  // inner join Product P on P.id = O.prod_id
  // where '{$_SESSION['department']}' = E.department_name AND
  // O.approved is null;";
  // $sqlGetOrderResult = mysqli_query($conn, $sqlGetOrder);

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
          //UPDATES
          header("Refresh:0");
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
  }

 ?>
