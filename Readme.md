# 🚒 Online Fire Reporting System (OFRS)

**A Smart Emergency Response & Fire Incident Management Platform**

The **Online Fire Reporting System (OFRS)** is a full-stack web application designed to modernize traditional fire emergency reporting and management. By replacing slow, paper-based workflows with a secure digital platform, OFRS significantly reduces response times and enhances coordination between citizens and emergency response teams.

Built with a **dual-module architecture**, the system seamlessly connects the public with fire departments through real-time reporting, location tracking, team management, and analytical insights.

---

# ✨ Features

## 📍 Live Location Detection & Interactive Mapping

### 🌍 Automatic GPS Location Capture
- Automatically detects the user's location.
- Captures accurate latitude and longitude coordinates.
- Reduces manual input for faster emergency dispatch.

### 🗺️ Interactive Map Integration
- Powered by **Leaflet.js** and **OpenStreetMap**.
- Displays incident locations dynamically.
- Enhances situational awareness for administrators.

---

## 📊 Real-Time Analytics Dashboard

### 📈 Interactive Data Visualization
Powered by **Chart.js**, the dashboard provides:
- 📡 Radar Charts for threat severity analysis.
- 📉 Line Charts for incident trends.
- 📊 Dynamic graphical reports.

### ⚡ Quick Summary Cards
Instant overview of:
- Total Reports
- Pending Cases
- Active Dispatches
- Completed Operations

---

## 📰 Live Fire News Feed

Integrated News API provides:
- Regional fire incidents
- Safety alerts
- Emergency updates
- Disaster awareness information

---

## 👥 User & Admin Management

### 🧑 User Portal

Users can quickly submit incidents by providing:
- Full Name
- Contact Number
- Exact Location
- Fire Severity
- Additional Details

### 🔐 Admin Portal

Secure administrative dashboard featuring:
- Authentication
- Session Management
- Report Monitoring
- Team Allocation
- Analytics Dashboard

---

## ⏱️ Real-Time Report Tracking

### 🎫 Unique Tracking ID

Every fire report receives a unique tracking number.

### 🔄 Report Workflow

```
🆕 New
   ↓
📋 Assigned
   ↓
🚒 Team En Route
   ↓
✅ Completed
```

### 📝 Audit Logging

Every status update is automatically stored in:
- `tblfiretequesthistory`
- `tbl_audit_logs`

Providing complete incident history tracking.

---

## 🚒 Fire Response Team Management

Administrators can:

- ✅ Create Teams
- ✅ Assign Team Leaders
- ✅ Manage Team Members
- ✅ Allocate Fire Zones
- ✅ Update Team Details
- ✅ Delete Teams

Example Teams:
- Team Alpha
- Team Bravo
- Team Charlie

---

## 🚨 Safety Rules & Emergency Contacts

### 🛡️ Fire Safety Awareness

Includes:
- P.A.S.S Fire Extinguisher Method
- Evacuation Guidelines
- Fire Prevention Tips
- Hazard Awareness

### ☎️ Emergency Numbers

| Service | Number |
|---------|----------|
| 🚒 Fire Brigade | **101** |
| 🚨 General Emergency | **112** |
| 🚑 Ambulance | **108** |

---

# 🛠️ Tech Stack

## 🎨 Frontend
- HTML5
- CSS3
- JavaScript (ES6)

## ⚙️ Backend
- PHP 8.x
- PDO
- MySQLi

## 🗄️ Database
- MySQL
- MariaDB

## 🌍 APIs & Libraries
- Leaflet.js
- OpenStreetMap
- Chart.js
- News API

## 💻 Development Environment
- XAMPP
- Apache Server
- Visual Studio Code

---

# 🗃️ Database Architecture

The system uses five primary tables:

| Table | Description |
|--------|-------------|
| `tbladmin` | Admin authentication and profiles |
| `tblfirereport` | Fire incident records and geolocation |
| `tblfiretequesthistory` | Incident status history |
| `tblteams` | Fire response teams |
| `tblsite` | Global site configuration |

---

# ⚙️ Installation

## 1. Clone the Repository

```bash
git clone https://github.com/jills08/Online-fire-reporting-system.git
```

---

## 2. Move Project Files

Place the project folder inside:

```
C:\xampp\htdocs\
```

---

## 3. Configure Database

### Start Services

Start:
- Apache
- MySQL

using XAMPP Control Panel.

### Open phpMyAdmin

```
http://localhost/phpmyadmin
```

### Create Database

```
ofrsdb
```

### Import SQL File

Import the provided `.sql` file from the project database folder.

---

## 4. Run the Application

Open:

```
http://localhost/Online-fire-reporting-system
```

Login to the Admin Dashboard using administrator credentials.

---

# 🎯 Core Functionalities

## User Module
- Submit Fire Reports
- Auto Location Detection
- Track Incident Status
- View Fire Safety Guidelines
- Access Emergency Numbers
- Read Live Fire News

## Admin Module
- Secure Login
- Manage Reports
- Update Incident Status
- Allocate Fire Teams
- Dashboard Analytics
- Audit Logging
- Site Management

---

# 🚀 Future Enhancements

Potential upgrades:

- 📱 Progressive Web App (PWA)
- 🔔 SMS Notifications
- 📧 Email Alerts
- 📸 Image & Video Uploads
- 🤖 AI Fire Severity Prediction
- 🚁 Live Vehicle Tracking
- 🌐 Multi-language Support
- 📍 Google Maps Integration
- ☁️ Cloud Deployment

---

# 🤝 Contributing

Contributions are welcome!

You can:
- 🍴 Fork the repository
- 🐛 Report issues
- ✨ Suggest new features
- 🔧 Submit pull requests

---

# 📜 License

This project is developed for **public safety management and educational purposes**.

Feel free to use, modify, and contribute to improve emergency response systems.
<div align="center">
<img width="1877" height="908" alt="Screenshot 2026-04-07 045133" src="https://github.com/user-attachments/assets/5c9592ee-1963-4761-94d0-9dae9ef25921" /> 
<img width="1919" height="898" alt="Screenshot 2026-04-07 045149" src="https://github.com/user-attachments/assets/7082708d-338e-4d60-ace8-ebe02c4fab3b" />
<img width="1266" height="896" alt="Screenshot 2026-04-07 045229" src="https://github.com/user-attachments/assets/d8df7579-04e2-46f0-a8dc-57dcf52f19e7" />
<img width="1312" height="908" alt="Screenshot 2026-04-07 045303" src="https://github.com/user-attachments/assets/422ebdb2-c06a-44e4-a5db-cbe4ded84683" />
<img width="602" height="605" alt="Screenshot 2026-04-07 045315" src="https://github.com/user-attachments/assets/3eff322d-153d-4ecb-a352-990bf8d69f0f" />
<img width="487" height="683" alt="Screenshot 2026-04-07 033813" src="https://github.com/user-attachments/assets/4b852dca-6d5b-4d55-83a5-9d6133befca4" />
<img width="630" height="660" alt="Screenshot 2026-04-07 045347" src="https://github.com/user-attachments/assets/33139080-7cf2-4ff6-ae11-bb6adf0c4214" />
<img width="1919" height="897" alt="Screenshot 2026-04-07 050700" src="https://github.com/user-attachments/assets/b39a082d-9efe-4137-9ba3-59241ba2da10" />
<img width="1423" height="507" alt="Screenshot 2026-04-07 050727" src="https://github.com/user-attachments/assets/cb900a9a-2cbb-4613-944b-9a1f538ff257" />
<img width="649" height="678" alt="Screenshot 2026-04-07 050740" src="https://github.com/user-attachments/assets/215833db-af3a-48f8-9575-8099a2e44980" />
<img width="1594" height="755" alt="Screenshot 2026-04-07 050804" src="https://github.com/user-attachments/assets/0873f49d-7d49-4b97-9525-2ad0fcfd4c4c" />
</div>
---

<div align="center">

## 🚒 Every Second Counts. Every Report Matters.

### Building smarter emergency response systems through technology.

⭐ **If you found this project useful, don't forget to Star the repository!**

</div>
