<?php
session_start();

// Initialize reminders array in session if not exists
if (!isset($_SESSION['reminders'])) {
    $_SESSION['reminders'] = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = htmlspecialchars($_POST['title'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $save_cookie = isset($_POST['save_cookie']);
            
            if (!empty($title) && !empty($date) && !empty($time)) {
                $reminder = [
                    'id' => uniqid(),
                    'title' => $title,
                    'description' => $description,
                    'date' => $date,
                    'time' => $time,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                // Save to session
                $_SESSION['reminders'][] = $reminder;
                
                // Save to cookie if requested (expires in 30 days)
                if ($save_cookie) {
                    $cookie_reminders = isset($_COOKIE['reminders']) ? json_decode($_COOKIE['reminders'], true) : [];
                    $cookie_reminders[] = $reminder;
                    setcookie('reminders', json_encode($cookie_reminders), time() + (30 * 24 * 60 * 60), '/');
                }
                
                $success_message = "Reminder added successfully!";
            } else {
                $error_message = "Please fill in all required fields.";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? '';
            
            // Delete from session
            $_SESSION['reminders'] = array_filter($_SESSION['reminders'], function($r) use ($id) {
                return $r['id'] !== $id;
            });
            $_SESSION['reminders'] = array_values($_SESSION['reminders']);
            
            // Delete from cookie
            if (isset($_COOKIE['reminders'])) {
                $cookie_reminders = json_decode($_COOKIE['reminders'], true);
                $cookie_reminders = array_filter($cookie_reminders, function($r) use ($id) {
                    return $r['id'] !== $id;
                });
                $cookie_reminders = array_values($cookie_reminders);
                setcookie('reminders', json_encode($cookie_reminders), time() + (30 * 24 * 60 * 60), '/');
            }
            
            $success_message = "Reminder deleted successfully!";
        } elseif ($_POST['action'] === 'clear_all') {
            $_SESSION['reminders'] = [];
            setcookie('reminders', '', time() - 3600, '/');
            $success_message = "All reminders cleared!";
        } elseif ($_POST['action'] === 'load_from_cookie') {
            if (isset($_COOKIE['reminders'])) {
                $cookie_reminders = json_decode($_COOKIE['reminders'], true);
                $_SESSION['reminders'] = $cookie_reminders;
                $success_message = "Reminders loaded from cookies!";
            } else {
                $error_message = "No reminders found in cookies.";
            }
        }
    }
}

// Get all reminders (from session)
$reminders = $_SESSION['reminders'] ?? [];

// Check if there are reminders in cookies
$has_cookie_reminders = isset($_COOKIE['reminders']) && !empty(json_decode($_COOKIE['reminders'], true));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder App - Session & Cookie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📝 Reminder Application</h1>
            <p class="subtitle">Manage your reminders with Sessions & Cookies</p>
        </header>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="info-box">
            <h3>ℹ️ How it works:</h3>
            <ul>
                <li><strong>Session Storage:</strong> Reminders are stored in PHP sessions (temporary, cleared when browser closes)</li>
                <li><strong>Cookie Storage:</strong> Check "Save to Cookie" to persist reminders for 30 days</li>
                <li><strong>Load from Cookie:</strong> Restore previously saved reminders from cookies</li>
            </ul>
        </div>

        <?php if ($has_cookie_reminders && empty($reminders)): ?>
            <div class="cookie-notice">
                <p>🍪 You have reminders saved in cookies!</p>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="load_from_cookie">
                    <button type="submit" class="btn btn-secondary">Load from Cookies</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h2>Add New Reminder</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required placeholder="Enter reminder title">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Enter reminder description (optional)"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Date *</label>
                        <input type="date" id="date" name="date" required>
                    </div>

                    <div class="form-group">
                        <label for="time">Time *</label>
                        <input type="time" id="time" name="time" required>
                    </div>
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="save_cookie" value="1">
                        Save to Cookie (persist for 30 days)
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Add Reminder</button>
            </form>
        </div>

        <div class="reminders-container">
            <div class="reminders-header">
                <h2>Your Reminders (<?php echo count($reminders); ?>)</h2>
                <?php if (!empty($reminders)): ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="clear_all">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to clear all reminders?')">Clear All</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (empty($reminders)): ?>
                <div class="empty-state">
                    <p>No reminders yet. Add your first reminder above!</p>
                </div>
            <?php else: ?>
                <div class="reminders-grid">
                    <?php foreach ($reminders as $reminder): ?>
                        <?php
                        $reminder_datetime = strtotime($reminder['date'] . ' ' . $reminder['time']);
                        $is_past = $reminder_datetime < time();
                        $is_today = date('Y-m-d', $reminder_datetime) === date('Y-m-d');
                        ?>
                        <div class="reminder-card <?php echo $is_past ? 'past' : ($is_today ? 'today' : ''); ?>">
                            <div class="reminder-header">
                                <h3><?php echo $reminder['title']; ?></h3>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $reminder['id']; ?>">
                                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this reminder?')">×</button>
                                </form>
                            </div>
                            
                            <?php if (!empty($reminder['description'])): ?>
                                <p class="reminder-description"><?php echo $reminder['description']; ?></p>
                            <?php endif; ?>
                            
                            <div class="reminder-datetime">
                                <span class="date">📅 <?php echo date('M d, Y', $reminder_datetime); ?></span>
                                <span class="time">🕐 <?php echo date('h:i A', $reminder_datetime); ?></span>
                            </div>
                            
                            <?php if ($is_past): ?>
                                <span class="badge badge-past">Past</span>
                            <?php elseif ($is_today): ?>
                                <span class="badge badge-today">Today</span>
                            <?php endif; ?>
                            
                            <div class="reminder-meta">
                                <small>Created on: <?php echo date('M d, Y h:i A', strtotime($reminder['created_at'])); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Set minimum date to today
        document.getElementById('date').min = new Date().toISOString().split('T')[0];
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>
