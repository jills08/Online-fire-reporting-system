# 🚒 Online Fire Reporting System (OFRS) #

**A Smart Emergency Response & Fire Incident Management Platform**

The **Online Fire Reporting System (OFRS)** is a full-stack web application designed to modernize traditional fire emergency reporting and management. By replacing slow, paper-based workflows with a secure digital platform, OFRS significantly reduces response times and enhances coordination between citizens and emergency response teams.

Built with a **dual-module architecture**, the system seamlessly connects the public with fire departments through real-time reporting, location tracking, team management, and analytical insights.

---

# ✨ Key Features

## 📍 Live Location Detection & Interactive Mapping

### 🌍 Automatic GPS Location Capture

* Detects the user's current location.
* Automatically populates latitude and longitude coordinates.
* Reduces manual entry errors for faster dispatch.

### 🗺️ Interactive Map Integration

* Powered by **Leaflet.js** and **OpenStreetMap**.
* Displays incident locations dynamically.
* Helps administrators visualize and coordinate emergency responses.

---

## 📊 Real-Time Analytics Dashboard

### 📈 Smart Data Visualization

The admin dashboard includes interactive analytics powered by **Chart.js**, featuring:

* 📡 Radar Charts for threat severity analysis.
* 📉 Line Charts for tracking incident trends over time.
* 📊 Dynamic graphical reporting.

### ⚡ Dashboard Summary Cards

Quick overview panels displaying:

* Total Reports
* Pending Cases
* Active Dispatches
* Completed Operations

---

## 📰 Live Fire News Feed

Stay informed with real-time fire-related news updates using News API integration.

### Benefits:

* Current regional incidents.
* Safety updates.
* Disaster awareness.
* Additional situational context.

---

## 👥 User & Admin Management

### 🧑 Public User Portal

A simple reporting interface allowing users to submit fire incidents in under a minute.

Users can provide:

* Full Name
* Contact Number
* Exact Location
* Fire Severity
* Additional Details

### 🔐 Secure Admin Command Center

A protected administration panel featuring:

* Authentication & Session Management
* Incident Monitoring
* Report Management
* Team Allocation
* Dashboard Analytics

---

## ⏱️ Real-Time Report Tracking

### 🎫 Unique Tracking ID

Every submitted report receives a unique tracking number.

### 🔄 Status Workflow

Reports progress through multiple operational stages:

```
🆕 New
   ↓
📋 Assigned
   ↓
🚒 Team En Route
   ↓
✅ Completed
```

### 📝 Automated Audit Logging

Every status update is automatically recorded in:

* `tblfiretequesthistory`
* `tbl_audit_logs`

This creates a complete historical timeline for accountability and forensic tracking.

---

## 🚒 Fire Response Team Management

Administrators can efficiently manage emergency response units with full CRUD functionality.

### Features:

✅ Create Teams

✅ Assign Team Leaders

✅ Manage Team Members

✅ Allocate Active Fire Zones

✅ Update Team Details

✅ Remove Teams

Example:

* Team Alpha
* Team Bravo
* Team Charlie

---

## 🚨 Fire Safety & Emergency Resources

### 🛡️ Public Safety Awareness

The platform provides educational guidance for emergency preparedness, including:

* Fire extinguisher usage (P.A.S.S Method)
* Evacuation procedures
* Fire prevention tips
* Hazard awareness

### ☎️ Emergency Contact Directory

Quick access to essential emergency numbers:

| Service              | Number  |
| -------------------- | ------- |
| 🚒 Fire Brigade      | **101** |
| 🚑 Ambulance         | **108** |
| 🚨 General Emergency | **112** |

---

# 🛠️ Technology Stack

## 🎨 Frontend

* HTML5
* CSS3
* JavaScript (ES6)

## ⚙️ Backend

* PHP 8.x
* PDO
* MySQLi

## 🗄️ Database

* MySQL
* MariaDB

## 🌍 APIs & Libraries

* Leaflet.js
* OpenStreetMap
* Chart.js
* News API

## 💻 Development Environment

* XAMPP
* Apache Server
* VS Code

---

# 🗃️ Database Architecture

The application operates using five primary relational tables:

| Table                   | Purpose                                    |
| ----------------------- | ------------------------------------------ |
| `tbladmin`              | Admin authentication and profiles          |
| `tblfirereport`         | Fire incident reports and geolocation data |
| `tblfiretequesthistory` | Incident status history                    |
| `tblteams`              | Fire response team management              |
| `tblsite`               | Global site configuration                  |

---

# ⚙️ Installation Guide

## 1️⃣ Clone the Repository

```bash
git clone https://github.com/jills08/Online-fire-reporting-system.git
```

---

## 2️⃣ Move Project Files

Copy the project folder to your local server directory:

```
C:\xampp\htdocs\
```

---

## 3️⃣ Configure the Database

### Start Services

Open XAMPP and start:

* Apache
* MySQL

### Open phpMyAdmin

```
http://localhost/phpmyadmin
```

### Create Database

```
ofrsdb
```

### Import SQL File

Import the provided `.sql` file from the project's database folder.

---

## 4️⃣ Run the Application

Open your browser:

```
http://localhost/Online-fire-reporting-system
```

Login to the Admin Dashboard using the configured administrator credentials.

---

# 🎯 Core Functionalities

## User Module

* Submit fire reports
* Auto location detection
* Track incident status
* Access safety guidelines
* View emergency contacts
* Read live fire news

## Admin Module

* Secure authentication
* Manage reports
* Update incident status
* Allocate response teams
* View analytics dashboard
* Maintain audit logs
* Manage platform settings

---

# 🚀 Future Enhancements

Potential improvements include:

* 📱 Progressive Web App (PWA)
* 🔔 SMS & Email Notifications
* 📸 Photo & Video Evidence Upload
* 🤖 AI-Based Fire Severity Prediction
* 🚁 Real-Time Vehicle Tracking
* 🌐 Multi-Language Support
* 📍 Google Maps Integration
* ☁️ Cloud Deployment

---

# 🤝 Contributing

Contributions are welcome!

You can:

* 🍴 Fork the repository
* 🐛 Report bugs
* ✨ Suggest features
* 🔧 Submit pull requests

---

# 📜 License

This project was developed for **public safety management and educational purposes**.

Feel free to use, modify, and contribute to further enhance emergency response systems and community safety.

---

<div align="center">

## 🚒 Every Second Counts. Every Report Matters.

**Building smarter emergency response systems through technology.**

⭐ If you found this project useful, consider giving it a star!

</div>
