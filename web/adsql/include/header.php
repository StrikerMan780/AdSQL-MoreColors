<?php
include("config.php");
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title><?php echo COMMUNITYNAME . " - AdsQL Advertisements Manager"; ?></title>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/jquery.jqEasyCharCounter.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$('.countable_adtext').jqEasyCounter({
			'maxChars': 1024,
			'maxCharsWarning': 190,
			'msgFontSize': '12px',
			'msgFontColor': '#000',
			'msgFontFamily': 'Verdana',
			'msgTextAlign': 'left',
			'msgWarningColor': '#F00',
			'msgAppendMethod': 'insertBefore'				
		});

		$('.countable_adserver').jqEasyCounter({
			'maxChars': 64,
			'maxCharsWarning': 60,
			'nocomment' : true,
			'msgFontSize': '12px',
			'msgFontColor': '#000',
			'msgFontFamily': 'Verdana',
			'msgTextAlign': 'left',
			'msgWarningColor': '#F00',
			'msgAppendMethod': 'insertBefore'				
		});
});
</script>
</head>
<body>
