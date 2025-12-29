<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
*{box-sizing:border-box;font-family:Arial}
body{margin:0;background:#eef3ff}

.sidebar{
position:fixed;left:0;top:0;height:100vh;width:220px;
background:#7c96f7;padding:20px;color:#fff
}
.sidebar a{
display:block;margin:15px 0;color:#fff;cursor:pointer
}

.main{margin-left:220px;padding:30px}

.stats{
display:grid;grid-template-columns:repeat(3,1fr);
gap:20px;margin-bottom:30px
}
.stat-card{background:#fff;padding:20px;border-radius:12px}

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:20px
}
.event-card{
background:#fff;border-radius:12px;overflow:hidden
}
.event-card img{width:100%;height:140px;object-fit:cover}
.event-card h4{margin:10px}
.event-card p{margin:0 10px;color:#555}
.meta{margin:5px 10px;font-size:13px;color:#777}

.actions{display:flex;gap:10px;padding:10px}
.actions button{border:none;padding:6px 10px;border-radius:5px}
.edit{background:#f1c40f}
.delete{background:#e74c3c;color:#fff}

.form-box{
background:#fff;padding:20px;border-radius:12px;
max-width:400px;margin-bottom:30px
}
input{width:100%;padding:10px;margin-bottom:10px}
.save{background:#27ae60;color:#fff;padding:10px;border:none;width:100%}
</style>
</head>

<body>

<script>
if(!localStorage.getItem("adminLoggedIn")){
    window.location.href="login.php";
}
</script>

<div class="sidebar">
<h3>Admin</h3>
<a onclick="showDashboard()">Dashboard</a>
<a onclick="showForm()">Create Event</a>
<a onclick="logout()">Logout</a>
</div>

<div class="main">

<div id="dashboardView">
<div class="stats">
<div class="stat-card">Total Events <h3 id="total">0</h3></div>
<div class="stat-card">Upcoming <h3 id="upcoming">0</h3></div>
<div class="stat-card">Active Today <h3 id="active">0</h3></div>
</div>

<div class="cards" id="cards"></div>
</div>

<div id="formView" style="display:none">
<div class="form-box">
<h3 id="formTitle">Create Event</h3>
<input id="title" placeholder="Event Title">
<input id="place" placeholder="Location">
<input type="date" id="date">
<button class="save" onclick="saveEvent()">Save</button>
</div>
</div>

</div>

<script>
let events = JSON.parse(localStorage.getItem("events")) || [];
let editIndex=null;

function showForm(){
dashboardView.style.display="none";
formView.style.display="block";
}
function showDashboard(){
formView.style.display="none";
dashboardView.style.display="block";
}

function saveEvent(){
let e={title:title.value,place:place.value,date:date.value};
if(!e.title||!e.place||!e.date){alert("Fill all");return;}

if(editIndex!==null){events[editIndex]=e;editIndex=null;}
else{events.push(e);}

localStorage.setItem("events",JSON.stringify(events));
render();
showDashboard();
title.value=place.value=date.value="";
}

function render(){
cards.innerHTML="";
let today=new Date().toISOString().split("T")[0];
let up=0,ac=0;

events.forEach((e,i)=>{
if(e.date>today)up++;
if(e.date===today)ac++;

cards.innerHTML+=`
<div class="event-card">
<img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87">
<h4>${e.title}</h4>
<p>${e.place}</p>
<div class="meta">${e.date}</div>
<div class="actions">
<button class="edit" onclick="editEvent(${i})">Edit</button>
<button class="delete" onclick="delEvent(${i})">Delete</button>
</div>
</div>`;
});

total.innerText=events.length;
upcoming.innerText=up;
active.innerText=ac;
}

function editEvent(i){
let e=events[i];
title.value=e.title;place.value=e.place;date.value=e.date;
editIndex=i;showForm();
}

function delEvent(i){
events.splice(i,1);
localStorage.setItem("events",JSON.stringify(events));
render();
}

function logout(){
localStorage.removeItem("adminLoggedIn");
window.location.href="login.php";
}

render();
</script>

</body>
</html>
