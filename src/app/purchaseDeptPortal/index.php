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
        $getInkoopOrder = "
        select id, prod_naam, prod_omschrijving, reden, employee_id, approved, added
        from Inkoop
        order by id desc;";
        $sqlGetInkoopOrder = mysqli_query($conn, $getInkoopOrder);

        //overziht van de openstaande invoices.
        //wanneer betaald is een notificatie naar depManPortal.
       ?>
       <div class="inkoopContainer">
         <h2>Openstaande nieuwe product aanvraag</h2>
         <table>
           <thead>
             <th> ID</th>
             <th> Product naam </th>
             <th> Product omschrijving </th>
             <th> Reden</th>
             <th> Employee ID</th>
             <th> Approved </th>
             <th> Added </th>
             <th> Actie </th>
           </thead>
           <tbody>
             <?php
             while ($record = mysqli_fetch_assoc($sqlGetInkoopOrder)) {
               echo "<tr>
                        <td>{$record['id']}</td>
                        <td>{$record['prod_naam']}</td>
                        <td>{$record['prod_omschrijving']}</td>
                        <td>{$record['reden']}</td>
                        <td>{$record['employee_id']}</td>
                        <td>{$record['approved']}</td>
                        <td>{$record['added']}</td>
                        <td>
                        <form method='post'>
                         <input type='submit' name='betalen{$record['id']}' value='voeg toe aan Orders'>
                        </form>
                        </td>
                    </tr>";
             }
             ?>
           </tbody>
         </table>
       </div>
    </main>
</body>

</html>
