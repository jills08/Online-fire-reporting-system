</main>
<footer class="site-footer" id="footer">
  <div class="container footer-grid">
    <div>
      <h3>OFRS</h3>
      <p>Built for major project evaluation with public reporting, live tracking, searchable incident records, map support, and safety awareness guidance.</p>
    </div>
    <div>
      <h4>Quick Links</h4>
      <ul>
        <li><a href="reporting.php">Report an Incident</a></li>
        <li><a href="search.php">Search Reports</a></li>
        <li><a href="fire-safety.php">Safety Guide</a></li>
      </ul>
    </div>
    <div>
      <h4>Emergency Numbers</h4>
      <ul>
        <li><a href="tel:101">Fire Brigade 101</a></li>
        <li><a href="tel:112">Emergency 112</a></li>
        <li><a href="tel:108">Ambulance 108</a></li>
      </ul>
    </div>
  </div>
  <div class="container footer-bottom">
    <span>DB: ofrsdb | User: admin | Pass: Test@123</span>
    <span>PHP + MySQL + Leaflet + OpenStreetMap</span>
  </div>
  <div id="contactModal" class="modal-overlay">
  <div class="modal-content glass-card">
    <button id="closeModalBtn" class="modal-close">&times;</button>
    <h3 style="font-family: 'Syne', sans-serif; font-size: 24px; color: var(--text); margin-bottom: 25px;">Emergency Dispatch</h3>
    
    <ul class="contact-list">
      <li>
        <div class="contact-info">
          <strong style="color: var(--neon-red);">Fire Brigade</strong>
          <span style="font-size: 12px; color: var(--muted);">Direct Line</span>
        </div>
        <a href="tel:101" class="call-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          101
        </a>
      </li>
      <li>
        <div class="contact-info">
          <strong style="color: var(--neon-orange);">General Emergency</strong>
          <span style="font-size: 12px; color: var(--muted);">Police / Rescue</span>
        </div>
        <a href="tel:112" class="call-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          112
        </a>
      </li>
      <li>
        <div class="contact-info">
          <strong style="color: #2ed573;">Ambulance</strong>
          <span style="font-size: 12px; color: var(--muted);">Medical Support</span>
        </div>
        <a href="tel:108" class="call-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          108
        </a>
      </li>
    </ul>
  </div>
</div>
</footer>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="assets/js/main.js" defer></script>
</body>
</html>
