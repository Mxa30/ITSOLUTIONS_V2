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
      <div class="ordersContainer">
        <h2>Binnengekomen verzoeken</h2>
        <table class="ordersTable">
          <thead>
            <th>Naam</th>
            <th>Product</th>
            <th>Totaalprijs</th>
            <th>Aantal</th>
            <th>Reden</th>
            <th>Actie</th>
          </thead>
          <tbody>
            <?php

              $orders = [];
              while ($record = mysqli_fetch_assoc($sqlGetOrderResult)) {
                $orders[] = $record;
              }
              $_SESSION['orders'] = $orders;
              if (!empty($_SESSION['orders'])) {
                foreach($_SESSION['orders'] as $record){
                  $totalPrice = $record['price']*$record['amount'];
                  echo(
                    "
                    <tr>
                    <td>{$record['empName']}</td>
                    <td>{$record['prodName']}</td>
                    <td>€{$totalPrice}</td>
                    <td>{$record['amount']}</td>
                    <td>{$record['reason']}</td>
                    <td>
                    <form method='post'>
                    <button type='submit' name='accept{$record['id']}'>Goedkeuren</button>
                    </form>
                    <form method='post'>
                    <select name='reason{$record['id']}'>
                    <option value='1'>Recent al gekocht</option>
                    <option value='2'>Overbodig artikel</option>
                    <option value='3'>Geen budget voor</option>
                    <option value='4'>Niet meer leverbaar</option>
                    <option value='5'>Externe omstandigheden</option>
                    </select>
                    <textarea name='budgetReason{$record['id']}' placeholder='Vul alleen in als er geen budget is'></textarea>
                    <button type='submit' name='deny{$record['id']}'>Afkeuren</button>
                    </form>
                    </td>
                    </tr>
                    "
                  );
                }
              }else {
                echo(
                  "
                  <tr>
                  <table class='emptyTable'>
                    <tr>
                      <td>Er zijn geen nieuwe verzoeken meer</td>
                    </tr>
                  </table>
                  </tr>
                  "
                );
              }
            ?>
          </tbody>
        </table>
      </div>
    </main>

</body>

</html>
