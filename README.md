# 📝 Reminder Application - PHP with Sessions & Cookies

A simple yet powerful reminder application built with PHP, HTML, and CSS that demonstrates the use of **PHP Sessions** and **Cookies** for data persistence.

## 🌟 Features

- **Add Reminders**: Create reminders with title, description, date, and time
- **Session Storage**: Reminders stored in PHP sessions (temporary)
- **Cookie Storage**: Optional persistent storage for 30 days
- **Visual Indicators**: Color-coded cards for today's and past reminders
- **Delete Reminders**: Remove individual reminders or clear all at once
- **Load from Cookies**: Restore previously saved reminders from cookies
- **Responsive Design**: Beautiful UI that works on all devices
- **Real-time Status**: Display session ID and cookie status

## 🛠️ Technologies Used

- **PHP**: Server-side logic, session and cookie management
- **HTML5**: Structure and forms
- **CSS3**: Modern styling with gradients and animations
- **JavaScript**: Client-side enhancements

## 📋 Requirements

- PHP 7.4 or higher
- Web server (Apache, Nginx, or PHP built-in server)
- Web browser with cookies enabled

## 🚀 Installation & Setup

1. **Clone or download** this project to your local machine

2. **Navigate** to the project directory:
   ```bash
   cd reminder-app
   ```

3. **Start PHP built-in server**:
   ```bash
   php -S localhost:8000
   ```

4. **Open your browser** and visit:
   ```
   http://localhost:8000
   ```

## 📖 How It Works

### Sessions
- Reminders are automatically stored in PHP sessions
- Session data is temporary and cleared when the browser closes
- Each user gets a unique session ID

### Cookies
- Check "Save to Cookie" when adding a reminder to persist it for 30 days
- Cookies survive browser restarts
- Use "Load from Cookies" button to restore saved reminders

### Data Flow
1. User submits a reminder form
2. PHP processes the data and stores it in `$_SESSION['reminders']`
3. If "Save to Cookie" is checked, data is also stored in a cookie
4. Reminders are displayed from the session
5. On page reload, session data persists (until browser closes)
6. Cookie data can be loaded back into the session

## 🎨 Features Breakdown

### Add Reminder
- **Title**: Required field for reminder name
- **Description**: Optional details about the reminder
- **Date**: When the reminder is due
- **Time**: Specific time for the reminder
- **Save to Cookie**: Checkbox to persist reminder

### View Reminders
- **Today Badge**: Yellow badge for today's reminders
- **Past Badge**: Red badge for overdue reminders
- **Delete Button**: Remove individual reminders
- **Clear All**: Remove all reminders at once

### Status Display
- **Session ID**: Shows current PHP session identifier
- **Cookie Status**: Indicates if cookies are active

## 🔒 Security Features

- HTML special characters are escaped using `htmlspecialchars()`
- CSRF protection through POST requests
- Input validation for required fields
- Confirmation dialogs for destructive actions

## 📱 Responsive Design

The application is fully responsive and works seamlessly on:
- Desktop computers
- Tablets
- Mobile phones

## 🎯 Use Cases

- Personal task management
- Event reminders
- Meeting schedules
- Birthday reminders
- Deadline tracking

## 🔧 Customization

### Change Cookie Duration
Edit line 26 in `index.php`:
```php
setcookie('reminders', json_encode($cookie_reminders), time() + (30 * 24 * 60 * 60), '/');
```
Change `30` to desired number of days.

### Modify Styling
Edit `style.css` to customize:
- Colors and gradients
- Card layouts
- Font sizes
- Animations

## 📝 File Structure

```
reminder-app/
│
├── index.php       # Main application file (PHP + HTML)
├── style.css       # Styling and responsive design
└── README.md       # Documentation
```

## 🐛 Troubleshooting

### Cookies not working?
- Ensure cookies are enabled in your browser
- Check that you're not in incognito/private mode
- Verify the server is running on a proper domain (not file://)

### Session not persisting?
- Check PHP session configuration
- Ensure session_start() is called before any output
- Verify write permissions for session directory

### Reminders disappearing?
- Session reminders clear when browser closes (expected behavior)
- Use "Save to Cookie" option for persistence
- Check if cookies are being blocked

## 🎓 Learning Points

This project demonstrates:
- PHP session management with `$_SESSION`
- Cookie creation and reading with `setcookie()` and `$_COOKIE`
- Form handling with POST requests
- Data serialization with JSON
- HTML form validation
- CSS Grid and Flexbox layouts
- Responsive web design
- Client-side JavaScript enhancements

## 📄 License

This project is open source and available for educational purposes.

## 🤝 Contributing

Feel free to fork this project and add your own features!

## 📧 Support

If you encounter any issues or have questions, please create an issue in the repository.

---

**Happy Reminding! 📝✨**
