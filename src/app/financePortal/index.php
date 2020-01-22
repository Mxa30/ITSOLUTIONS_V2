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
      <?php
      //sql query om order informatie op te vragen.
        $getOrder = "
        select id, approved, picked_up, delivered, prod_id, amount, paid
        from _Order
        where approved = 1
        order by id desc;";
        $sqlGetFinanceOrderResult = mysqli_query($conn, $getOrder);

        //overziht van de openstaande invoices.
        //wanneer betaald is een notificatie naar depManPortal.
       ?>
       <div class="orderContainer">
         <h2>Openstaande orders</h2>
         <table>
           <thead>
             <th> ID</th>
             <th> Approved </th>
             <th> Deliverd </th>
             <th> Picked up </th>
             <th> Product ID</th>
             <th> Amount </th>
             <th> Paid </th>
           </thead>
           <tbody>
             <?php
             while ($record = mysqli_fetch_assoc($sqlGetFinanceOrderResult)) {
               echo "<tr>
                        <td>{$record['id']}</td>
                        <td>{$record['approved']}</td>
                        <td>{$record['deliverd']}</td>
                        <td>{$record['picked_up']}</td>
                        <td>{$record['prod_id']}</td>
                        <td>{$record['amount']}</td>
                        <td>{$record['paid']}</td>
                    </tr>";
             }
             ?>
           </tbody>
         </table>
       </div>
    </main>
</body>

</html>
