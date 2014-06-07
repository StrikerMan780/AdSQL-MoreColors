<?php
function escape_value($value, $connid) {
	$magic_quotes_active = get_magic_quotes_gpc();
	$real_escape_string_exists = function_exists( "mysql_real_escape_string" );
	if (!is_array($value)) {
		if( $real_escape_string_exists ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $magic_quotes_active ) {
				$value = stripslashes( $value );
			}
			$value = mysql_real_escape_string( $value , $connid);
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$magic_quotes_active ) {
				$value = addslashes( $value );
			}
			// if magic quotes are active, then the slashes already exist
		}
	} else {
		foreach ($value as $key => $val) {
			$value[$key] = escape_value($val, $connid);
		}
	}
	return $value;

}
function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}


function cleanInput($input) {
 
$search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
);
 
    $output = preg_replace($search, '', $input);
    return $output;
}
?>