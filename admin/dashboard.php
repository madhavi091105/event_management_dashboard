<?php
session_start();
require_once "../config/database.php"; // Path check kar lena sahi ho

// 1. Database Connection initialize karein
$database = new Database();
$db = $database->getConnection();
// dashboard.php ke sabse upar (session check ke baad) ye add karein:
$stmt = $db->prepare("SELECT * FROM events ORDER BY id DESC");
$stmt->execute();
$db_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 1. Agar user logged in nahi hai, toh login page par bhej do
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { margin: 0; background: #eef3ff; display: flex; }

        /* Sidebar Style */
        .sidebar {
            position: fixed; left: 0; top: 0; height: 100vh; width: 240px;
            background: #7c96f7; padding: 25px; color: #fff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h3 { margin-bottom: 30px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px; }
        .sidebar a {
            display: block; margin: 15px 0; color: #fff; text-decoration: none;
            cursor: pointer; padding: 10px; border-radius: 5px; transition: 0.3s;
        }
        .sidebar a:hover { background: rgba(255,255,255,0.1); }

        /* Main Content Area */
        .main { margin-left: 240px; padding: 40px; width: 100%; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }

        /* Stats Cards */
        .stats {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 20px; margin-bottom: 30px;
        }
        .stat-card { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .stat-card h3 { margin: 10px 0 0 0; color: #7c96f7; font-size: 28px; }

        /* Event Cards */
        .cards {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .event-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .event-card img { width: 100%; height: 150px; object-fit: cover; }
        .event-card-content { padding: 15px; }
        .event-card h4 { margin: 0 0 10px 0; }
        .event-card p { margin: 5px 0; color: #555; font-size: 14px; }
        .meta { font-size: 12px; color: #888; margin-top: 10px; }

        /* Actions */
        .actions { display: flex; gap: 10px; padding: 15px; background: #f9f9f9; }
        .actions button { border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .edit { background: #f1c40f; color: #000; }
        .delete { background: #e74c3c; color: #fff; }

        /* Form Style */
        .form-box {
            background: #fff; padding: 30px; border-radius: 12px;
            max-width: 500px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 6px; }
        .save { background: #27ae60; color: #fff; padding: 12px; border: none; width: 100%; border-radius: 6px; cursor: pointer; font-size: 16px; }
    </style>
</head>

<body>

    <div class="sidebar">
        <h3>Admin Panel</h3>
        <p style="font-size: 14px; opacity: 0.8;">User: <?php echo $_SESSION["admin_username"]; ?></p>
        <a onclick="showDashboard()">📊 Dashboard</a>
        <a onclick="showForm()">➕ Create Event</a>
        <a href="logout.php" style="color: #ff9b9b;">🚪 Logout</a>
    </div>

    <div class="main">
        <div class="header-bar">
            <h2>Welcome, <?php echo $_SESSION["admin_username"]; ?>!</h2>
        </div>

        <div id="dashboardView">
            <div class="stats">
                <div class="stat-card">Total Events <h3 id="total">0</h3></div>
                <div class="stat-card">Upcoming <h3 id="upcoming">0</h3></div>
                <div class="stat-card">Active Today <h3 id="active">0</h3></div>
            </div>

            <div class="cards" id="cards">
                </div>
        </div>

        <div id="formView" style="display:none">
    <div class="form-box">
        <h3>Create New Event</h3>
        <form action="save_event.php" method="POST" id = "eventForm">
            <input type="hidden" name = "event_id" id = "event_id">

            <input name="title" id = "form_title" placeholder="Event Title" required>
            <input name="location"id = "form_location" placeholder="Location (Table: location)" required>
            <input type="date" name="event_date" id = "form_date" required>
            <textarea name="description" id = "form_desc" placeholder="Event Description" style="width:100%; height:100px; margin-bottom:10px;"></textarea>
            <button type="submit" id="submitBtn" class="save">Upload to Database</button>
        </form>
    </div>
</div>
    </div>

    <script>
       let events = <?php echo json_encode($db_events); ?>; 
       // Ab 'events' variable mein database ka real data aa jayega.
        let editIndex = null;

        function showForm() {
            document.getElementById("dashboardView").style.display = "none";
            document.getElementById("formView").style.display = "block";
        }

        function showDashboard() {
            document.getElementById("formView").style.display = "none";
            document.getElementById("dashboardView").style.display = "block";
            render();
        }

        function saveEvent() {
            let title = document.getElementById("title").value;
            let place = document.getElementById("place").value;
            let date = document.getElementById("date").value;

            if (!title || !place || !date) { alert("Please fill all fields"); return; }

            let e = { title, place, date };

            if (editIndex !== null) {
                events[editIndex] = e;
                editIndex = null;
            } else {
                events.push(e);
            }

            localStorage.setItem("events", JSON.stringify(events));
            document.getElementById("title").value = "";
            document.getElementById("place").value = "";
            document.getElementById("date").value = "";
            showDashboard();
        }

        function render() {
            let cardsContainer = document.getElementById("cards");
            cardsContainer.innerHTML = "";
            let today = new Date().toISOString().split("T")[0];
            let up = 0, ac = 0;

            events.forEach((e, i) => {
                if (e.date > today) up++;
                if (e.date === today) ac++;

                cardsContainer.innerHTML += `
                <div class="event-card">
                    <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=400">
                    <div class="event-card-content">
                        <h4>${e.title}</h4>
                        <p>📍 ${e.place}</p>
                        <div class="meta">📅 ${e.date}</div>
                    </div>
                    <div class="actions">
                        <button class="edit" onclick="editEvent(${i})">Edit</button>
                        <button class="delete" onclick="delEvent(${i})">Delete</button>
                    </div>
                </div>`;
            });

            document.getElementById("total").innerText = events.length;
            document.getElementById("upcoming").innerText = up;
            document.getElementById("active").innerText = ac;
        }

        function editEvent(i) {
    let e = events[i]; 
    
    // Form mein purana data bharna
    document.getElementById("event_id").value = e.id;
    document.getElementById("form_title").value = e.title;
    document.getElementById("form_location").value = e.location; // ya e.place jo bhi database column hai
    document.getElementById("form_date").value = e.event_date;
    document.getElementById("form_desc").value = e.description;

    // Form ka rasta badalna (IMPORTANT)
    document.getElementById("eventForm").action = "update_event.php";
    document.getElementById("submitBtn").innerText = "Update Event";

    showForm(); 
}

        function delEvent(i) {
            if(confirm("Are you sure?")) {
                events.splice(i, 1);
                localStorage.setItem("events", JSON.stringify(events));
                render();
            }
        }

        // Initial render
        render();
    </script>
</body>
</html>