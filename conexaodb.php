<?php
    class MyDB extends SQLite3 {
      function __construct() {
         //É necessário dar permissão para o usuario do apache
         //(chown -R www-data /var/www/html)
         $this->open('monitortemp.db', (SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE));
      }
   }
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "Banco aberto com sucesso\n";
   }
  ?>
