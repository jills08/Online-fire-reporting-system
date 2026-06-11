OFRS - Online Fire Reporting System

Run steps:
1. Create a folder named ofrs inside htdocs (XAMPP) or www (WAMP).
2. Copy all files and folders from this project into that ofrs folder.
3. Start Apache and MySQL.
4. Open phpMyAdmin and import database/ofrsdb.sql.
5. Ensure MySQL credentials are:
   DB Name: ofrsdb
   User: admin
   Password: Test@123
   Host: localhost
6. Open in browser:
   http://localhost/ofrs/

Main pages:
- index.php
- reporting.php
- search.php
- search-report-result.php
- details.php?id=1
- fire-safety.php

Notes:
- Uploaded evidence images will be stored inside uploads/.
- If your MySQL user is root instead of admin, update includes/config.php.
- Leaflet map uses OpenStreetMap tiles and runs without paid API keys.
