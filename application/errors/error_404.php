<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>404 Page Not Found</title>
<style type="text/css">

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

h1 {
	color: #444;
	background-color: transparent;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

#container {
	text-align: center;
	margin: 10px;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
<script type="text/javascript">
	setTimeout (function () { window.location.replace ('/'); }, 3000);
</script>
</head>
<body>
	<div id="container">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		(三秒後跳轉回首頁)
	</div>
</body>
</html>