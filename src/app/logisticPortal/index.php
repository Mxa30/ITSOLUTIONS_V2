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
        <table class="logisticTable">
          <thead>
            <tr>
              <th>Order Id</th>
              <th>Naam</th>
              <th>Department</th>
              <th>Product</th>
              <th>Aantal</th>
              <th>Actie</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Max van der Velde</td>
              <td>Logistics</td>
              <td>Laptop</td>
              <td>3</td>
              <td>Actie</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
</body>

</html>
