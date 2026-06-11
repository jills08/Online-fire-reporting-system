document.addEventListener('DOMContentLoaded', () => {
  // --- Navigation Toggle ---
  const burger = document.getElementById('navBurger');
  const nav = document.getElementById('mainNav');
  if (burger && nav) {
    burger.addEventListener('click', () => nav.classList.toggle('open'));
  }

  // --- Active Nav Link Highlighting ---
  const currentPage = location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.top-nav a').forEach(link => {
    if (link.getAttribute('href') === currentPage) link.classList.add('active');
  });

  // --- Toast Notification Auto-Hide ---
  const toast = document.getElementById('toast');
  if (toast) setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 400); }, 3500);

  // --- Theme Toggle ---
  const root = document.documentElement;
  const toggle = document.getElementById('themeToggle');
  let dark = true;
  if (toggle) {
    toggle.addEventListener('click', () => {
      dark = !dark;
      if (dark) {
        root.setAttribute('data-theme', 'dark');
        document.body.style.filter = '';
      } else {
        root.setAttribute('data-theme', 'light');
        document.body.style.filter = 'invert(1) hue-rotate(180deg)';
      }
    });
  }

  // --- Severity Button Logic ---
  document.querySelectorAll('.sev-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.closest('.severity-grid');
      if (!group) return;
      group.querySelectorAll('.sev-btn').forEach(b => b.className = 'sev-btn');
      const level = btn.dataset.level;
      btn.classList.add('active-' + level);
      const hidden = group.parentElement.querySelector('input[name="severity"]');
      if (hidden) hidden.value = level;
    });
  });

  // --- Photo Upload Preview ---
  const photoInput = document.getElementById('photoInput');
  const preview = document.getElementById('photoPreview');
  if (photoInput && preview) {
    photoInput.addEventListener('change', () => {
      preview.innerHTML = '';
      Array.from(photoInput.files).slice(0, 5).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.alt = file.name;
          preview.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    });
  }

  // --- GPS Location Capture ---
  const gpsBtn = document.getElementById('gpsBtn');
  if (gpsBtn) {
    gpsBtn.addEventListener('click', () => {
      if (!navigator.geolocation) return alert('Geolocation not supported.');
      gpsBtn.textContent = 'Locating...';
      navigator.geolocation.getCurrentPosition(pos => {
        const lat = pos.coords.latitude.toFixed(6);
        const lng = pos.coords.longitude.toFixed(6);
        const latEl = document.getElementById('latitude');
        const lngEl = document.getElementById('longitude');
        if (latEl) latEl.value = lat;
        if (lngEl) lngEl.value = lng;
        gpsBtn.textContent = 'Location Captured';
        if (window.reportMap) {
          window.reportMap.setView([lat, lng], 15);
          if (window.reportMarker) window.reportMap.removeLayer(window.reportMarker);
          window.reportMarker = L.marker([lat, lng], { draggable: true }).addTo(window.reportMap);
          window.reportMarker.on('dragend', e => {
            const ll = e.target.getLatLng();
            if (latEl) latEl.value = ll.lat.toFixed(6);
            if (lngEl) lngEl.value = ll.lng.toFixed(6);
          });
        }
      }, () => {
        gpsBtn.textContent = 'Use My GPS';
        alert('Unable to fetch location.');
      });
    });
  }

  // --- Incident Reporting Map Initialization ---
  const mapEl = document.getElementById('reportMap');
  if (mapEl && typeof L !== 'undefined') {
    const latEl = document.getElementById('latitude');
    const lngEl = document.getElementById('longitude');
    const startLat = parseFloat(latEl?.value || 19.0760);
    const startLng = parseFloat(lngEl?.value || 72.8777);
    const map = L.map('reportMap').setView([startLat, startLng], 12);
    window.reportMap = map;
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    window.reportMarker = L.marker([startLat, startLng], { draggable: true }).addTo(map);
    window.reportMarker.on('dragend', e => {
      const ll = e.target.getLatLng();
      if (latEl) latEl.value = ll.lat.toFixed(6);
      if (lngEl) lngEl.value = ll.lng.toFixed(6);
    });
    map.on('click', e => {
      const { lat, lng } = e.latlng;
      if (latEl) latEl.value = lat.toFixed(6);
      if (lngEl) lngEl.value = lng.toFixed(6);
      window.reportMarker.setLatLng(e.latlng);
    });
  }

  // --- Active Incidents Map Initialization ---
  const activeMapEl = document.getElementById('activeMap');
  if (activeMapEl && typeof L !== 'undefined') {
    const map = L.map('activeMap').setView([19.0760, 72.8777], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    if (window.activeReports && window.activeReports.length) {
      window.activeReports.forEach(item => {
        if (item.latitude && item.longitude) {
          const marker = L.marker([item.latitude, item.longitude]).addTo(map);
          marker.bindPopup(`<strong>${item.reference_code}</strong><br>${item.location_text}<br>${item.status}`);
        }
      });
    }
  }

  // ========================================================= 
  // CONTACT MODAL LOGIC                                       
  // ========================================================= 
  const modal = document.getElementById('contactModal');
  const closeBtn = document.getElementById('closeModalBtn');
  const contactTriggers = document.querySelectorAll('.open-contact');

  if (modal) {
    // Open Modal when any element with class 'open-contact' is clicked
    contactTriggers.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        modal.classList.add('active');
      });
    });

    // Close Modal when clicking the 'X' button
    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        modal.classList.remove('active');
      });
    }

    // Close Modal when clicking outside the modal content box
    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.remove('active');
      }
    });
  }

});