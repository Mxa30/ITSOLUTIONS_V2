<?php
include "../../meta.php";
include "functions.php";
?>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php
    // INCLUDE THE HEADER AND IT'S STYLESHEET
      include APP_PATH . "/header/index.php";
      include APP_PATH . "/header/functions.php";
      echo ("<link rel='stylesheet' href='" . APP_PATH . "/header/style.css". "'>");
    ?>
    <main>
        <div class="prodContainer">
          <h2>Producten</h2>
          <table>
            <thead>
              <th> Product</th>
              <th> Prijs </th>
              <th> Actie </th>
            </thead>
            <?php
            $options = "";
            for ($i=1; $i <= 50; $i++) {
              $options .= ("<option value='{$i}'>{$i}</option>");
            }
            if ($sqlGetProdResult) {
              if (!isset($_SESSION['prod'])){
                $prod = [];
                while ($record = mysqli_fetch_assoc($sqlGetProdResult)) {
                  $prod[] = $record;
                }
                $_SESSION['prod'] = $prod;
              }
              if (!empty($_SESSION['prod'])) {
                foreach($_SESSION['prod'] as $record){
                  echo (
                    "<tr>
                    <td>{$record['name']}</td>
                    <td>€{$record['price']}</td>
                    <td>
                    <form method='post'>
                    <select name='amount{$record['id']}'>{$options}</select>
                    <button type='submit' name='button{$record['id']}'>Add to cart</button>
                    </form>
                    </td>
                    </tr>"
                  );
                }
              }else {
                echo (
                  "<tr>
                    <table class='emptyProd'>
                      <tr>
                        <td>Er zijn op dit moment geen producten beschikbaar</td>
                      </tr>
                    </table>
                  </tr>"
                );
              }
            }else {
              echo (
                "<tr>
                  <table class='emptyProd'>
                    <tr>
                      <td>Er zijn op dit moment geen producten beschikbaar</td>
                    </tr>
                  </table>
                </tr>"
              );
            }
            ?>

          </table>
        </div>
        <div class="rightContainer">
          <div class="cartContainer">
            <h2>Cart</h2>
            <table class="cart">
              <form class='sendReqForm' method='post'>
                <thead>
                  <th> Product</th>
                  <th> Prijs </th>
                  <th> Aantal </th>
                  <th> Reden </th>
                </thead>
                <?php
                if (empty($_SESSION['cart'])) { //If cart is empty, show empty cart
                  echo (
                    "<tr>
                    <table class='emptyCart'>
                    <tr>
                    <td>Cart is leeg, voeg een product toe</td>
                    </tr>
                    </table>
                    </tr>"
                  );
                }else { //If cart is not empty, show the containing items
                  foreach ($_SESSION['cart'] as $prod) {
                    $options = "";
                    for ($i=1; $i <= 50; $i++) {
                      if ($i != $prod['amount']) {
                        $options .= ("<option value='{$i}'>{$i}</option>");
                      }else {
                        $options .= ("<option selected='selected' value='{$i}'>{$i}</option>");
                      }
                    }
                    echo (
                      "<tr>
                      <td>{$prod['name']}</td>
                      <td>€{$prod['price']}</td>
                      <td>
                      <select name='amountCart{$prod['id']}'>{$options}</select>
                      </td>
                      <td>
                      <textarea name='reason{$prod['id']}'></textarea>
                      <input type='submit' name='removeCartItem{$prod['id']}' value='Verwijder'>
                      </td>
                      </tr>"
                    );
                  }
                }
                ?>
              </table>
              <?php
              if (!empty($_SESSION['cart'])) { //If cart is empty, show empty cart
                echo (
                "<input class='sendCart' type='submit' name='submitRequest' value='Stuur koop-verzoek'>
              </form>"
              );
            }
            ?>

          </div>
          <div class="requestContainer">
            <h2>Koopverzoek status</h2>
            <table class="requestTable">
              <thead>
                <th> Product</th>
                <th> Totaalprijs </th>
                <th> Aantal </th>
                <th> Reden </th>
                <th> Status </th>
              </thead>
              <tbody>
                <?php
                if ($sqlGetOrderReqResult) {
                  // Load orders in session
                  $req = [];
                  while ($record = mysqli_fetch_assoc($sqlGetOrderReqResult)) {
                    $req[] = $record;
                  }
                  $_SESSION['requests'] = $req;
                  if (!empty($_SESSION['requests'])) { //If orders are not empty, show orders
                    foreach ($_SESSION['requests'] as $prod) {
                      if ($prod['approved'] == null) {
                        $status = "Goedkeuring afwachten";
                      }elseif ($prod['approved'] == 0) {
                        $status = "Geweigerd: " . $prod['deny_reason'];
                      }elseif ($prod['approved'] == 1 && $prod['delivered'] == null) {
                        $status = "Geaccepteerd - Bezorging afwachtend";
                      }elseif ($prod['approved'] == 1 && $prod['delivered'] == 1) {
                        $status = "Geleverd - Ophalen bij logistiek";
                      }elseif ($prod['approved'] == 1 && $prod['delivered'] == 0) {
                        $status = "Niet geleverd";
                      }elseif ($prod['approved'] == 1 && $prod['delivered'] == 1) {
                        $status = "Geleverd - Ophalen bij logistiek";
                      }

                      $totalOrderPrice = $prod['price']*$prod['amount'];
                      if ($prod['approved'] == 1 && $prod['delivered'] == 1) {
                        $approveDelivery ="<form><button type='submit' name='approveDelivery{$prod['id']}'>Keur product goed</button></form>";
                        echo (
                        "
                        <tr>
                          <td>{$prod['name']}</td>
                          <td>€{$totalOrderPrice}</td>
                          <td>{$prod['amount']}</td>
                          <td>{$prod['reason']}</td>
                          <td>
                            {$status}<br>{$approveDelivery}
                          </td>
                        </tr>
                        "
                        );
                      }else {
                        echo (
                        "
                        <tr>
                          <td>{$prod['name']}</td>
                          <td>€{$totalOrderPrice}</td>
                          <td>{$prod['amount']}</td>
                          <td>{$prod['reason']}</td>
                          <td>{$status}</td>
                        </tr>
                        "
                        );
                      }
                    }
                  }else { //If orders are not empty, show the containing orders
                    echo (
                    "
                    <tr>
                      <table class='emptyOrders'>
                        <tr>
                          <td>Geen uitgaande verzoeken</td>
                          <tr>
                          </table>
                        </tr>
                        "
                        );
                      }
                    }else { //If orders are not empty, show the containing orders
                      echo (
                      "
                      <tr>
                        <table class='emptyOrders'>
                          <tr>
                            <td>Geen uitgaande verzoeken</td>
                            <tr>
                            </table>
                          </tr>
                          "
                          );
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
        </div>
    </main>
</body>

</html>
