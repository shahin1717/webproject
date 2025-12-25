# 🚗 DriveX — Driving Experience Dashboard

**DriveX** is a modern, interactive web dashboard designed to track, analyze, and visualize driving practice data for learner drivers.  
It helps you monitor progress toward license requirements with real-time statistics, charts, and a clean UI.

---
## Link
**shahin.alwaysdata.net/webproject**
## ✨ Features

### 📊 Dashboard Overview
- Centralized view of **all driving experiences**
- Interactive **DataTable** with pagination, sorting, and search
- Export all data to **CSV** in one click

### 🎯 License Progress Tracking
- **Total Kilometers** progress (goal-based)
- **Total Driving Hours** progress
- **Maneuvers Mastered** tracking (dynamic from database)
- Smooth hover animations with motivational messages

### 📈 Data Visualization
- Monthly driving activity (line chart)
- Weather distribution
- Road surface distribution
- Traffic condition distribution
- Maneuver frequency overview

### 🛠 Experience Management
- Add, edit, and delete driving experiences
- Edit modal with full validation
- Delete confirmation modal for safety

### 🎨 UI / UX Highlights
- Fully responsive (desktop & mobile)
- Smooth hover animations on cards and charts
- Dark gradient theme with soft highlights
- Emoji-based visual cues for quick scanning

---

## 🧰 Tech Stack

### Frontend
- **HTML5**
- **CSS3** (custom responsive design)
- **JavaScript (ES6+)**
- **jQuery**
- **DataTables**
- **Chart.js**

### Backend
- **PHP**
- REST-style API endpoints
- JSON-based communication

### Database
- **MySQL**
- Normalized schema (experiences, weather, surface, traffic, maneuvers)

---

## 📂 Project Structure

```text
webproject/
├── dashboard.php              # Main dashboard UI
├── index.php                  # Landing page
├── WebForm.php                # Add experience form
│
├── includes/
│   ├─ includeDB.inc.php
│
├── routes/
│   ├── get_experiences.php
│   ├── get_static_data.php
│   ├── edit_experience.php
│   └── delete_experience.php
│
└─── classes/
    ├── DrivingExperience.php
    ├── Weather.php
    ├── Surface.php
    ├── Traffic.php
    └── Maneuver.php
