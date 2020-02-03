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
       <div class="orderContainer">
         <h2>Openstaande orders</h2>
         <form method="post" class="search">
           <input type="text" name="rearchOrderId" placeholder="Zoek op order id">
           <input type="submit" name="submitResId" value="Submit">
         </form>
         <table>
           <thead>
             <th> Order id</th>
             <th> Geleverd </th>
             <th> Product goedgekeurd </th>
             <th> Product ID</th>
             <th> Product naam</th>
             <th> Aantal </th>
             <th> Totaalprijs </th>
             <th> Betaald </th>
             <th> Actie </th>
           </thead>
           <tbody>
             <?php
             $financeOrders = [];
             while ($record = mysqli_fetch_assoc($sqlGetFinanceOrderResult)) {
               $financeOrders[] = $record;
             }
             $_SESSION['financeOrders'] = $financeOrders;
             if (!empty($_SESSION['financeOrders'])) {
               foreach ($_SESSION['financeOrders'] as $record) {
                 if ($record['delivered'] == 0) {
                   $delivered = "Nee";
                 }else {
                   $delivered = "Ja";
                 }

                 if ($record['picked_up'] == 0) {
                   $pickedUp = "Nee";
                 }else {
                   $pickedUp = "Ja";
                 }

                 if ($record['paid'] == 0) {
                   $paid = "Nee";
                 }else {
                   $paid = "Ja";
                 }

                 $totalPrice = $record['price']*$record['amount'];

                 $paidClassGreen = "";
                 if ($record['paid'] == 1) {
                   $paidClassGreen = "class='paidGreen'";
                 }
                 echo "<tr {$paidClassGreen}>
                 <td>{$record['id']}</td>
                 <td>{$delivered}</td>
                 <td>{$pickedUp}</td>
                 <td>{$record['prod_id']}</td>
                 <td>{$record['prodName']}</td>
                 <td>{$record['amount']}</td>
                 <td>€{$totalPrice}</td>
                 <td>{$paid}</td>
                 <td>
                 <form method='post'>
                 <button type='submit' name='pay{$record['id']}'>Betaald</button>
                 </form>
                 </td>
                 </tr>";
               }
             }else {
               echo "
               <table class='emptyOrder'>
                 <tr>
                   <td>Geen Orders gevonden</td>
                 </tr>
               </table>
               ";
             }
             ?>
           </tbody>
         </table>
       </div>
    </main>
</body>

</html>
