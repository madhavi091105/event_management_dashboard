<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<style>
body{
margin:0;height:100vh;
display:flex;align-items:center;justify-content:center;
background:#7c96f7;font-family:Arial
}
.card{
background:#fff;padding:30px;border-radius:10px;width:300px
}
input{
width:100%;padding:10px;margin-bottom:10px
}
button{
width:100%;padding:10px;background:#7c96f7;
border:none;color:#fff;cursor:pointer
}
</style>
</head>

<body>

<div class="card">
<h2>Admin Login</h2>
<input id="username" placeholder="Username">
<input id="password" type="password" placeholder="Password">
<button onclick="login()">Login</button>
</div>

<script src="app.js"></script>
</body>
</html>
