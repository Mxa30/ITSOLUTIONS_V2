<header>
  <div class="headerContainer">
    <img src="../../assets/unnamed.png" alt="" height="50px">
    <ul class="headerInfo">
      <li><b>Naam:</b> <?php echo($_SESSION['name']) ?></li>
      <li><b>Department:</b> <?php echo($_SESSION['department']) ?></li>
      <?php
      if ($_SESSION['is_dept_manager']) {
        $sqlGetBudget="
        select budget, rest_budget
        from department
        where name = '{$_SESSION['department']}';";
        $sqlGetBudgetResult = mysqli_query($conn, $sqlGetBudget);

        while ($record = mysqli_fetch_assoc($sqlGetBudgetResult)) {
          echo "
          <li><b>Budget:</b>{$record['budget']}</li>
          <li><b>Resterend budget:</b>{$record['rest_budget']}</li>
          ";
        }
      }
      ?>
    </ul>
    <ul class="headerNav">
      <form class="logOutForm"method="post">
      <a href="<?php echo(APP_PATH . "/buyportal/index.php");?>"><li>Koop portaal</li></a>
      <?php
      if ($_SESSION['is_dept_manager']) {
        echo(
          "<a href='" . APP_PATH . "/depmanportal/index.php" . "'><li>Department manager portaal</li></a>"
        );
      }
      if ($_SESSION['department'] == "Finance") {
        echo(
          "<a href='" . APP_PATH . "/financePortal/index.php" . "'><li>Finance portaal</li></a>"
        );
      }
      if ($_SESSION['department'] == "Logistics") {
        echo(
          "<a href='" . APP_PATH . "/logisticPortal/index.php" . "'><li>Logistiek portaal</li></a>"
        );
      }
      if ($_SESSION['department'] == "Purchasing") {
        echo(
          "<a href='" . APP_PATH . "/purchaseDeptPortal/index.php" . "'><li>Purchasing portaal</li></a>"
        );
      }
      ?>

        <button class="logOutButton" type="submit" name="logOut">
          <li>
            Log uit
          </li>
        </button>
      </form>
    </ul>
  </div>
</header>
