<?php
    class MyDB extends SQLite3 {
      function __construct() {
         $this->open('monitortemp.db');
      }
   }
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "Banco aberto com sucesso\n";
   }
  ?>