
<?php
// -----------------------------------------------------------------------
// DEFINE SEPERATOR ALIASES
// -----------------------------------------------------------------------
define("URL_SEPARATOR", '/');

define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINE ROOT PATHS
// -----------------------------------------------------------------------
defined('SITE_ROOT')? null: define('SITE_ROOT', realpath(dirname(__FILE__)));
define("LIB_PATH_INC", SITE_ROOT.DS);


require_once(LIB_PATH_INC.'config.php');
require_once(LIB_PATH_INC.'functions.php');
require_once(LIB_PATH_INC.'session.php');
require_once(LIB_PATH_INC.'upload.php');
require_once(LIB_PATH_INC.'database.php');
require_once(LIB_PATH_INC.'sql.php');

function find_stock_in() {
  global $db;
  $sql  = "SELECT stock_in.id, stock_in.quantity, stock_in.date_received, ";
  $sql .= "products.name AS product_name, suppliers.supplier_name ";
  $sql .= "FROM stock_in ";
  $sql .= "LEFT JOIN products ON stock_in.product_id = products.id ";
  $sql .= "LEFT JOIN suppliers ON stock_in.supplier_id = suppliers.id ";
  $sql .= "ORDER BY stock_in.date_received DESC";
  return find_by_sql($sql);
}


?>
