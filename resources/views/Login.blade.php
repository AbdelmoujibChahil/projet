<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>hello Login</h1>
    <a href="{{route('login')}}"></a>
<form action="" method="POST">
<label for="">email</label>
    <input  name="email" type="email"><br>
   <label for="">password</label> <input name="password" type="password"><br>
    <button>submit</button>
</form>
<a href="{{route('register.index')}}">creer compte</a>


</body>
</html>