<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	{{ $name }}
	@cache(1,86400)
		<select>
		    @foreach($list as $c)
		        <option value={{$c['id']}} >{{$c['name']}}</option>
		    @endforeach
		</select>
	@endcache()

</body>
</html>
