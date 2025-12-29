<!DOCTYPE html>
<html>
<head>
<title>User Events</title>

<style>
*{box-sizing:border-box;font-family:Arial}
body{margin:0;background:#eef3ff}

/* TOP BAR */
.topbar{
background:#7c96f7;color:#fff;
padding:15px 30px;font-size:20px
}

/* FILTER BAR */
.filter-bar{
background:#fff;
padding:15px 30px;
display:flex;
flex-wrap:wrap;
gap:15px;
align-items:center
}
.filter-bar input,
.filter-bar select{
padding:8px 10px;
border-radius:6px;
border:1px solid #ccc
}

/* MAIN */
.container{padding:30px}

/* EVENT GRID */
.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
gap:20px
}

.event-card{
background:#fff;border-radius:12px;
overflow:hidden;box-shadow:0 6px 15px rgba(0,0,0,.1)
}
.event-card img{
width:100%;height:140px;object-fit:cover
}
.event-card h3{margin:10px}
.event-card p{margin:0 10px;color:#555}
.meta{
margin:5px 10px 10px;
font-size:13px;color:#777
}
.badge{
display:inline-block;
margin:0 10px 10px;
padding:4px 8px;
border-radius:5px;
font-size:12px
}
.upcoming{background:#dff9fb;color:#0984e3}
.active{background:#d4efdf;color:#27ae60}
</style>
</head>

<body>

<div class="topbar">Events</div>

<!-- FILTERS -->
<div class="filter-bar">
    <input type="text" id="searchInput" placeholder="Search event name">
    
    <select id="statusFilter">
        <option value="all">All Events</option>
        <option value="upcoming">Upcoming</option>
        <option value="active">Active</option>
    </select>

    <input type="date" id="dateFilter">
</div>

<div class="container">
<div class="cards" id="cards"></div>
</div>

<script>
/* ---------- HELPERS ---------- */
function getStatus(date){
    let today = new Date().toISOString().split("T")[0];
    if(date === today) return "active";
    if(date > today) return "upcoming";
    return "past";
}

/* ---------- RENDER ---------- */
function renderUserEvents(){
    let events = JSON.parse(localStorage.getItem("events")) || [];
    let search = searchInput.value.toLowerCase();
    let status = statusFilter.value;
    let date   = dateFilter.value;

    cards.innerHTML = "";

    events.forEach(e=>{
        let eventStatus = getStatus(e.date);

        // filters
        if(eventStatus === "past") return;
        if(status !== "all" && eventStatus !== status) return;
        if(date && e.date !== date) return;
        if(search && !e.title.toLowerCase().includes(search)) return;

        cards.innerHTML += `
        <div class="event-card">
            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87">
            <h3>${e.title}</h3>
            <p>${e.place}</p>
            <div class="meta">🗓 ${e.date}</div>
            <span class="badge ${eventStatus}">
                ${eventStatus.toUpperCase()}
            </span>
        </div>`;
    });
}

/* ---------- EVENTS ---------- */
searchInput.addEventListener("input", renderUserEvents);
statusFilter.addEventListener("change", renderUserEvents);
dateFilter.addEventListener("change", renderUserEvents);

/* AUTO SYNC WITH ADMIN */
window.addEventListener("storage", renderUserEvents);

/* INITIAL LOAD */
renderUserEvents();
</script>

</body>
</html>
