<?php
  ob_start();

  //Linkt naar "IT_Solutions_BPM/source/" vanuit hier kan je door
  //linken naar bestanden binnen "/source"
  define("SOURCE_PATH", "../../../src");
  //Linkt naar "IT_Solutions_BPM/source/app" vanuit hier kan je door
  //linken naar bestanden binnen "/app"
  define("APP_PATH", dirname(SOURCE_PATH . "/app/."));
  //Linkt naar "IT_Solutions_BPM/source/asset" vanuit hier kan je door
  //linken naar bestanden binnen "/asset"
  define("ASSET_PATH", dirname(SOURCE_PATH . "/assets/."));

 ?>
