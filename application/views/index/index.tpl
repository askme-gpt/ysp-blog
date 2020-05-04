<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	{{ foreach from=$list item=item key=key }}
		{{ $item['id'] }} == 	{{ $item['name'] }}
		<br>
	{{ /foreach }}

</body>
</html>
