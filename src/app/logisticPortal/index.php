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
      <div class="logisticListContainer">
        <h2>Logistics List</h2>
        <form method="post" class="search">
          <input type="text" name="rearchOrderId" placeholder="Zoek op order id">
          <input type="submit" name="submitResId" value="Submit">
        </form>
        <table class="logisticTable">
          <thead>
            <tr>
              <th>Order Id</th>
              <th>Naam</th>
              <th>Department</th>
              <th>Product</th>
              <th>Product ID</th>
              <th>Aantal</th>
              <th>Actie</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $logisticOrders = [];
              while ($record = mysqli_fetch_assoc($sqlGetLogisticsResult)) {
                $logisticOrders[] = $record;
              }
              $_SESSION['logisticOrders'] = $logisticOrders;

              if (!empty($_SESSION['logisticOrders'])) {
                foreach($_SESSION['logisticOrders'] as $record){
                  echo("
                  <tr>
                    <td>{$record['id']}</td>
                    <td>{$record['empName']}</td>
                    <td>{$record['department_name']}</td>
                    <td>{$record['prodName']}</td>
                    <td>{$record['prodID']}</td>
                    <td>{$record['amount']}</td>
                    <td>
                    <form method='post'>
                      <button type='submit' name='deliver{$record['id']}'>Geleverd</button>
                    </form>
                    </td>
                  </tr>
                  ");
                }
              }else {
                echo("
                <table class='emptyLogistic'>
                  <tr>
                    <td>Er zijn geen nieuwe bestellingen meer</td>
                  </tr>
                </table>
                ");
              }
            ?>
          </tbody>
        </table>
      </div>
    </main>
</body>

</html>
