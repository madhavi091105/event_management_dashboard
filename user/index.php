<?php
require_once "../config/database.php"; 

$database = new Database();
$db = $database->getConnection();
$stmt = $db->prepare("SELECT * FROM events WHERE status = 'active' ORDER BY event_date ASC");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Events</title>
    <style>
        body { font-family: 'Arial', sans-serif; background: #eef3ff; padding: 40px; margin: 0; }
        h1 { text-align: center; color: #333; margin-bottom: 40px; }
        .container { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 25px; 
            max-width: 1200px;
            margin: 0 auto;
        }
        .event-card { 
            background: white; 
            padding: 20px; 
            border-radius: 15px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .event-card:hover { transform: translateY(-5px); }
        .event-card h2 { color: #7c96f7; margin-top: 0; font-size: 22px; }
        .loc { font-weight: bold; color: #555; margin-bottom: 10px; display: block; }
        .desc { color: #666; font-size: 14px; line-height: 1.5; height: 60px; overflow: hidden; }
        .date { 
            margin-top: 15px; 
            padding-top: 15px; 
            border-top: 1px solid #eee; 
            color: #888; 
            font-size: 0.85em; 
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <h1>Upcoming Events</h1>
    <div class="container">
        <?php if (count($events) > 0): ?>
            <?php foreach($events as $row): ?>
                <div class="event-card">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <span class="loc">📍 <?php echo htmlspecialchars($row['location'] ?? 'Venue TBD'); ?></span>
                    <p class="desc"><?php echo htmlspecialchars($row['description'] ?? 'No description provided.'); ?></p>
                    <div class="date">
                        <span>📅 <?php echo $row['event_date']; ?></span>
                        <span>⏰ <?php echo $row['event_time'] ?? ''; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1/-1;">Abhi koi naya event nahi hai.</p>
        <?php endif; ?>
    </div>
</body>
</html>
