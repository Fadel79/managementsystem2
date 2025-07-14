<?php
$errors = array();

/*--------------------------------------------------------------*/
/* Function for escaping special characters for SQL statement */
/*--------------------------------------------------------------*/
function real_escape($str) {
  global $con;
  $escape = mysqli_real_escape_string($con, $str);
  return $escape;
}

/*--------------------------------------------------------------*/
/* Function to remove HTML tags and encode special characters */
/*--------------------------------------------------------------*/
function remove_junk($str) {
  if (is_null($str)) {
    return '';
  }
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str), ENT_QUOTES);
  return $str;
}

/*--------------------------------------------------------------*/
/* Function to capitalize the first character */
/*--------------------------------------------------------------*/
function first_character($str) {
  if (is_array($str)) return ''; // prevent fatal error
  $val = str_replace('-', " ", $str);
  $val = ucfirst($val);
  return $val;
}

/*--------------------------------------------------------------*/
/* Function for validating required input fields */
/*--------------------------------------------------------------*/
function validate_fields($fields) {
  global $errors;
  foreach ($fields as $field) {
    $val = isset($_POST[$field]) ? remove_junk($_POST[$field]) : '';
    if (trim($val) === '') {
      $errors[] = ucwords(str_replace('_', ' ', $field)) . " can't be blank.";
    }
  }
}

/*--------------------------------------------------------------*/
/* Function for displaying session messages */
/*--------------------------------------------------------------*/
function display_msg($msg = '') {
  $output = '';
  if (!empty($msg) && is_array($msg)) {
    foreach ($msg as $type => $messages) {
      // pastikan messages adalah array
      if (!is_array($messages)) {
        $messages = [$messages];
      }

      foreach ($messages as $message) {
        $output .= "<div class=\"alert alert-{$type}\">";
        $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
        $output .= remove_junk(first_character($message));
        $output .= "</div>";
      }
    }
  }
  return $output;
}


/*--------------------------------------------------------------*/
/* Function for redirecting to another page */
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false) {
  if (headers_sent() === false) {
    header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
  }
  exit();
}

/*--------------------------------------------------------------*/
/* Function to calculate total price and profit */
/*--------------------------------------------------------------*/
function total_price($totals) {
  $sum = 0;
  $sub = 0;
  foreach ($totals as $total) {
    $sum += $total['total_saleing_price'];
    $sub += $total['total_buying_price'];
  }
  $profit = $sum - $sub;
  return array($sum, $profit);
}

/*--------------------------------------------------------------*/
/* Function for formatting readable datetime */
/*--------------------------------------------------------------*/
function read_date($str) {
  return $str ? date('F j, Y, g:i:s a', strtotime($str)) : null;
}

/*--------------------------------------------------------------*/
/* Function to generate current datetime */
/*--------------------------------------------------------------*/
function make_date() {
  return strftime("%Y-%m-%d %H:%M:%S", time());
}

/*--------------------------------------------------------------*/
/* Function for incrementing table row count */
/*--------------------------------------------------------------*/
function count_id() {
  static $count = 1;
  return $count++;
}

/*--------------------------------------------------------------*/
/* Function to generate a random string */
/*--------------------------------------------------------------*/
function randString($length = 5) {
  $str = '';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";
  for ($x = 0; $x < $length; $x++)
    $str .= $cha[mt_rand(0, strlen($cha) - 1)];
  return $str;
}
?>
