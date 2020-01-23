<?php
if ($_SESSION['logged'] != true || $_SESSION['department'] != "Finance") {
  $redirectlocation = APP_PATH . "/login/index.php";
  return header("location: {$redirectlocation}");
}

  $getOrder = "
  select O.id, O.approved, O.picked_up, O.delivered, O.prod_id, O.amount, O.paid, P.price, P.name prodName
  from _Order O, Product P
  where approved = 1 and
  O.prod_id = P.id
  order by id desc;";
  $sqlGetFinanceOrderResult = mysqli_query($conn, $getOrder);

  function payInvoice($conn, $orderId) {
    $sqlPayOrder = "
    UPDATE `_order`
    SET paid = '1'
    WHERE `id` = '{$orderId}';";

    if (mysqli_query($conn, $sqlPayOrder)) {
      // UPDATE SUCCESFUL
    }else{
      echo "Error: " . $sqlPayOrder . "<br>" . mysqli_error($conn);
    }
  }

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

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    while($record = mysqli_fetch_assoc($sqlGetFinanceOrderResult)){
      if (isset($_POST['pay' . $record['id']])) {
        payInvoice($conn, $record['id']);
        header("Refresh:0");
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
