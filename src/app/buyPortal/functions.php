<?php
  if ($_SESSION['logged'] != true) {
    $redirectlocation = APP_PATH . "/login/index.php";
    return header("location: {$redirectlocation}");
  }

  // SQL QUERYS
  // Get all products
  $sqlGetProd = "
  select id, name, price, supplier
  from Product
  order by id desc;";
  $sqlGetProdResult = mysqli_query($conn, $sqlGetProd);

  // Get outgoing orders
  $sqlGetOrderReq = "
  select O.id, P.name, P.price, O.amount, O.reason, O.approved, O.deny_reason, O.delivered
  from _Order O
  inner join Product P on P.id = O.prod_id
  where '{$_SESSION['employee_id']}' = O.employee_id AND
  O.picked_up is null;";

  $sqlGetOrderReqResult = mysqli_query($conn, $sqlGetOrderReq);

  // Initialize cart in global scope
  if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
  }

  function addToCart($id, $name, $supplier, $price, $amount) {
    // loop trough already existing cart items
    foreach ($_SESSION['cart'] as $key => $prod) {
      if ($prod['id'] == $id) { // If item is already in cart add selected amount to cart
        $_SESSION['cart'][$key]['amount'] = $_SESSION['cart'][$key]['amount'] + $amount;
        return getCart();
      }
    }
    // If the cart doesn't have the selected item, add it to the cart
    array_push($_SESSION['cart'], ["id"=>$id, "name"=>$name, "supplier"=>$supplier, "price"=>$price, "amount"=>$amount]);
    return getCart();
  }

  function removeCartItem($prodId) {
    foreach ($_SESSION['cart'] as $key => $prod) {
      if ($prod['id'] == $prodId) {
        unset($_SESSION['cart'][$key]);
        return getCart();
      }
    }
  }

  // Get the cart array
  function getCart() {
    return $_SESSION['cart'];
  }

  function submitCart($conn,$prod,$reason) {
      $sqlPostOrderQuery = "
      INSERT INTO `_order` (`prod_id`, `amount`, `employee_id`, `reason`)
      VALUES ('{$prod['id']}', '{$_POST['amountCart' . $prod['id']]}', '{$_SESSION['employee_id']}','{$reason}');";

      if (mysqli_query($conn, $sqlPostOrderQuery)) {

      }else{
        echo "Error: " . $sqlPostOrderQuery . "<br>" . mysqli_error($conn);
      }
  }

  function pickUp($conn, $orderId) {
    $sqlPickedUpOrderQuery = "
    UPDATE `_order`
    SET picked_up = '1'
    WHERE `id` = {$orderId};";

    if (mysqli_query($conn, $sqlPickedUpOrderQuery)) {
      //UPDATES
      header("Refresh:0");
    }else{
      echo "Error: " . $sqlPickedUpOrderQuery . "<br>" . mysqli_error($conn);
    }
  }

  // Find the rigth post request for calling the functions
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    while($record = mysqli_fetch_assoc($sqlGetProdResult)){
      if (isset($_POST['button' . $record['id']])) {
        // Call addToCart function with the parameters form the according product
        addToCart($record['id'], $record['name'], $record['supplier'], $record['price'], $_POST['amount' . $record['id']]);
        header("Refresh:0");
      }
      if (isset($_POST['removeCartItem' . $record['id']])) {
        removeCartItem($record['id']);
        header("Refresh:0");
      }
      if (isset($_POST['submitRequest'])) {
        foreach ($_SESSION['cart'] as $prod) {
          if (empty($_POST['reason' . $prod['id']])) {
            header("Refresh:0");
          }
        }
        // Call the function submitCart if the button "Stuur koop-verzoek" is pressed
        foreach ($_SESSION['cart'] as $prod) {
          if (empty($_POST['reason' . $prod['id']])) {
            header("Refresh:0");
          }else {
            submitCart($conn,$prod,$_POST['reason' . $prod['id']]);
            removeCartItem($prod['id']);
            header("Refresh:0");
          }
        }
        // $_SESSION['cart'] = [];

      }
    }
    while($record = mysqli_fetch_assoc($sqlGetOrderReqResult)){
      if (isset($_POST['approveDelivery' . $record['id']])) {
        pickUp($conn, $record['id']);
      }
    }
  }
?>
