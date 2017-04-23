<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<form action="../Controller/adminLogin.php" method="POST">
    <label for="">Email</label>
    <input type="email" name="email" required>
    <label for="">Password</label>
    <input type="password" name="password" required>
    <input type="submit" value="Login">
</form>
</body>
</html>