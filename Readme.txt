Here is a highly professional, visually engaging, and comprehensive description tailored perfectly for your GitHub repository's `README.md`. It incorporates your structural details, code architecture, and highlight features like the live location mapping, News API integration, and tactical analytics dashboard!

---

# 🔥 Online Fire Reporting System (OFRS)

An advanced, full-stack web application designed to revolutionize emergency communication and management. By transitioning from slow, legacy paper-based workflows to a dynamic, digital environment, the **Online Fire Reporting System (OFRS)** drastically minimizes response times when every second counts. Built with a dual-module architecture, it bridges the gap between citizens needing immediate help and tactical response teams looking for structural dispatch workflows.

---

## 🚀 Key Technical Highlights & Features

### 📍 Live Location Scan & Map Integration

* 
**Tactical GPS Scanning:** Built-in integration using a dedicated location mapping workflow. Users can automatically map coordinates, instantly populating Latitude and Longitude fields via precise positioning mechanics.


* 
**Interactive Map API:** Leverages the **Leaflet.js API** coupled with **OpenStreetMap** tiles. This displays an active map layout on the interface to showcase incidents geographically.



### 📊 Real-Time Analytics Dashboard

* 
**Dynamic Threat Metrics:** Powered by **Chart.js** inside the secure admin backend. Renders full analytical graphs, including a **Radar Chart** for analyzing multidimensional threat severity matrices alongside **Line Charts** tracking incident density over time.


* 
**Quick Metric Cards:** Provides rapid data visualization metrics right on the main dashboard for an instantaneous high-level overview.



### 📰 Live Fire News Feed (News API)

* Keeps citizens and dispatchers aware of current regional events by populating external context onto the platform, working alongside native reporting features to offer a robust informational experience.

### 👥 Granular User & Admin Controls

* 
**Public Portal (User Module):** Simple, fast, and structured form layout that captures vital information (Full Name, Contact, Exact Location, and Severity Levels) in under 60 seconds.


* 
**Command Center (Admin Module):** A fully secure, password-protected environment equipped with dedicated session handling and security authentications.



### ⏱️ Instant Tracking & Status Re-assignments

* Users receive a unique tracking ID immediately upon submission to monitor the real-time lifecycle of their report.


* Admins can seamlessly update status entries across operational check-points: `New` $\rightarrow$ `Assigned` $\rightarrow$ `Team en route` $\rightarrow$ `Completed`.


* Every adjustment triggers an audit trail pipeline, logging the changes automatically within `tblfiretequesthistory` and generating comprehensive forensic tracking across `tbl_audit_logs`.



### 🚒 Setting Up & Managing Various Teams

* Complete CRUD management tools built for the administration layer. Admins can seamlessly configure, allocate, deploy, and delete distinct fire response teams (e.g., *Team Alpha*), assigning specific team leaders, contact rosters, and member lists to individual active fire zones.



### 🚨 Safety Rules & Emergency Contact Numbers

* 
**Public Awareness Hub:** Includes a dedicated reference sector for citizens outlining quick, actionable guides during active crises (e.g., Extinguisher usage via the **P.A.S.S.** method, evacuation procedures, and critical hazard warnings).


* 
**Direct Dispatch Lines:** Provides a clean interface display listing vital regional toll-free emergency hotlines (such as Fire Brigade: 101, General Emergency: 112, Ambulance: 108) for immediate fallback communication.



---

## 🛠️ Tech Stack & Technologies Used

* 
**Front End Interaction:** HTML5, CSS3, JavaScript (ES6) 


* 
**Back End Scripting Engine:** PHP 8.x (utilizing PDO for secure geospatial analytics and MySQLi for core configurations) 


* 
**Relational Database:** MySQL / MariaDB (Optimized schema configurations with cross-table relationships) 


* 
**Geospatial API:** Leaflet.js mapping with OpenStreetMap tiles 


* 
**Data Visualization:** Chart.js engine 


* 
**Local Development Environment:** XAMPP / Apache Web Server 


* 
**IDE:** VS Code 



---

## 🗄️ Database Architecture Diagram Overview

The relational structure runs efficiently across five primary relational databases:

1. 
`tbladmin`: Secure management profiles and login authentications.


2. 
`tblfirereport`: Core tracking entity holding names, contact numbers, exact location strings, and geospatial coordinate entries.


3. 
`tblfiretequesthistory`: Audit logs compiling historical timeline records for all tracking updates.


4. 
`tblteams`: Active dispatch crew registries, team names, and leader information.


5. 
`tblsite`: Global parameters, site titles, and graphic asset logs.



---

## 🔧 Installation & Local Setup

To deploy this project locally on your machine, follow these steps:

1. **Clone the Repository:**
```bash
git clone https://github.com/jills08/Online-fire-reporting-system.git

```


2. **Move files to Server Directory:**
Place the cloned project folder inside your local server directory (e.g., `C:\xampp\htdocs\` for XAMPP).
3. **Database Setup:**
* Open XAMPP Control Panel and start **Apache** and **MySQL**.


* Navigate to `http://localhost/phpmyadmin`.
* Create a new database named `ofrsdb`.


* Import the structured `.sql` file provided in the database directory of this project.




4. **Run the Application:**
* Open your web browser and navigate to: `http://localhost/Online-fire-reporting-system`.


* Access the Tactical Command Dashboard using the admin credentials.





---

### 📝 License

This project is built for public safety management and educational development purposes. Feel free to fork, submit issues, or create pull requests to enhance the capabilities of this system! 🚒✨
