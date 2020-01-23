<?php
include "../../meta.php";
include "functions.php";
?>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="javascript.js"></script>
</head>

<body>
    <?php
    // INCLUDE THE HEADER AND IT'S STYLESHEET
      include APP_PATH . "/header/index.php";
      include APP_PATH . "/header/functions.php";
      echo ("<link rel='stylesheet' href='" . APP_PATH . "/header/style.css". "'>");
    ?>
    <main>
       <div class="inkoopContainer">
         <h2>Openstaande nieuwe product aanvraag</h2>
         <table>
           <thead>
             <th> ID</th>
             <th> Product naam </th>
             <th> Product omschrijving </th>
             <th> Reden</th>
             <th> Employee ID</th>
             <th> Toegevoegd </th>
             <th> Actie </th>
           </thead>
           <tbody>
             <?php
             $purchaceOrders = [];
             while ($record = mysqli_fetch_assoc($sqlGetInkoopOrder)) {
               $purchaceOrders[] = $record;
             }
             $_SESSION['purchaceOrders'] = $purchaceOrders;
             if (!empty($_SESSION['purchaceOrders'])) {
               foreach($_SESSION['purchaceOrders'] as $record) {
                 if ($record['added'] == null || $record['added'] == 0) {
                   $added = "Nee";
                 }else {
                   $added = "Ja";
                 }
                 echo "<tr>
                          <td>{$record['id']}</td>
                          <td>{$record['prod_naam']}</td>
                          <td>{$record['prod_omschrijving']}</td>
                          <td>{$record['reden']}</td>
                          <td>{$record['employee_id']}</td>
                          <td>{$added}</td>
                          <td>
                          <form method='post' onsubmit='return openForm({$record['id']})'>
                           <button type='submit' name='betalen{$record['id']}'>Voeg product toe</button>
                          </form>
                          </td>
                      </tr>";
               }
             }else {
               echo("
               <table class='emptyPurchase'>
                 <tr>
                   <td>Er zijn geen nieuwe product bestellingen meer</td>
                 </tr>
               </table>
               ");
             }
             ?>
           </tbody>
         </table>
       </div>
       <div class="inkoopFormulier" id="inkoopFormulierID">
         <div class="X" onclick="return closeForm()">
           <div class="line1"></div>
           <div class="line2"></div>
         </div>
         <h2>Nieuw product</h2>
         <form method="post" class="newProdForm">
           <input type="text" name="prodName" placeholder="Product naam*" required>
           <input type="number" step="0.01" name="prodPrice" placeholder="Product prijs*" required>
           <label>Leverancier</label>
           <select name="prodSupplier">
             <option value="0">Nieuwe leverancier</option>
             <?php
             while ($record = mysqli_fetch_assoc($sqlGetSuppliersResult)) {
               echo("
               <option value='{$record['name']}'>{$record['name']}</option>
               ");
             }
             ?>
           </select>
           <input type="text" name="supplierName" placeholder="Leverancier naam">
           <input type="email" name="supplierEmail" placeholder="Leverancier email">
           <button id="submitNewProd" type="submit" name="submitNewProd">Voeg product toe</button>
         </form>
       </div>
    </main>
</body>

</html>
