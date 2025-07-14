
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
  $sql  = "SELECT si.id, si.quantity, si.date_received, ";
  $sql .= "si.is_quantity_ok, si.is_quality_ok, si.validation_note, ";
  $sql .= "p.name AS product_name, p.stock AS current_stock, ";
  $sql .= "s.supplier_name, s.mobile_phone ";
  $sql .= "FROM stock_in si ";
  $sql .= "LEFT JOIN products p ON si.product_id = p.id ";
  $sql .= "LEFT JOIN suppliers s ON si.supplier_id = s.id ";
  $sql .= "ORDER BY si.date_received DESC";

  return find_by_sql($sql);
}

function find_stock_out() {
  global $db;
  $sql  = "SELECT so.*, p.name AS product_name, p.stock, c.customer_name, c.address ";
  $sql .= "FROM stock_out so ";
  $sql .= "LEFT JOIN products p ON so.product_id = p.id ";
  $sql .= "LEFT JOIN customers c ON so.customer_id = c.id ";
  $sql .= "ORDER BY so.date_received DESC";
  return find_by_sql($sql);
}

?>
