<?php
// dashboard.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Driving Experience - Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- jQuery + DataTables -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet"
    href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

  <!-- Chart.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

  <style>
    /* ====== GLOBAL RESET & BASE ====== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body {
      min-height: 100vh;
    }

    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1d1f33, #4e5d9d);
      color: #ffffff;
      display: flex;
      flex-direction: column;
    }




    a {
      color: inherit;
    }

    /* ====== TOP BAR (A) ====== */
    .topbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 60px;
      background: #242842;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      z-index: 1000;
    }

    .topbar-title {
      font-size: 1.3rem;
      font-weight: bold;
      color: #ffb347;
      letter-spacing: 0.05em;
    }

    .topbar-nav {
      display: flex;
      gap: 1rem;
    }

    .topbar-link {
      color: #c3c6df;
      text-decoration: none;
      font-size: 0.95rem;
      padding: 0.35rem 0.8rem;
      border-radius: 999px;
      border: 1px solid transparent;
      transition: 0.25s ease;
    }

    .topbar-link:hover {
      color: #ffb347;
      border-color: #ffb347;
      background: rgba(255, 179, 71, 0.1);
    }

    /* ====== MESSAGE BAR ====== */
    #messageBar {
      position: fixed;
      top: 70px;
      left: 50%;
      transform: translateX(-50%);
      min-width: 260px;
      max-width: 420px;
      padding: 0.75rem 1.2rem;
      border-radius: 10px;
      background: #fef9e7;
      color: #333a6e;
      font-size: 0.95rem;
      text-align: center;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
      opacity: 0;
      pointer-events: none;
      transition: 0.4s ease;
      z-index: 900;
    }

    #messageBar.visible {
      opacity: 1;
      pointer-events: auto;
    }

    /* ====== PAGE CONTENT WRAPPER ====== */
    .page-content {
      flex: 1;
      width: 100%;
      max-width: 1200px;
      margin: 90px auto 40px auto;
      padding: 0 1rem 40px 1rem;
    }

    /* ====== SECTION WRAP ====== */
    .section-card {
      background: #333a6e;
      border-radius: 16px;
      padding: 1.4rem 1.6rem;
      margin-bottom: 1.2rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    }

    .section-title {
      font-size: 1.2rem;
      font-weight: bold;
      color: #ffb347;
      margin-bottom: 0.8rem;
    }

    /* ====== DATATABLE (D) ====== */
    table.dataTable thead {
      background: #242842;
      color: #ffb347;
    }

    table.dataTable tbody tr {
      background: #2a3056;
      color: #ffffff;
    }

    table.dataTable tbody tr:nth-child(even) {
      background: #30376a;
    }

    table.dataTable tbody tr:hover {
      background: #3a427c;
    }

    .icon-label {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.15rem 0.6rem;
      border-radius: 999px;
      background: rgba(15, 23, 42, 0.5);
      border: 1px solid rgba(148, 163, 184, 0.4);
      font-size: 0.85rem;
    }

    /* GREEN EDIT BUTTON */
    .btn-edit {
      padding: 0.35rem 0.8rem;
      border-radius: 999px;
      border: 1px solid #4ade80;
      /* green border */
      background: rgba(74, 222, 128, 0.12);
      /* soft green */
      color: #bbf7d0;
      /* light green text */
      cursor: pointer;
      font-size: 0.85rem;
      transition: 0.2s ease;
    }

    .btn-edit:hover {
      background: #4ade80;
      /* bright green */
      color: #1f2937;
      /* dark text */
    }

    .icon-label span.icon {
      font-size: 1rem;
    }

    .icon-label span.text {
      font-size: 0.85rem;
    }

    .btn-delete {
      padding: 0.35rem 0.8rem;
      border-radius: 999px;
      border: 1px solid #f97373;
      background: rgba(248, 113, 113, 0.12);
      color: #fecaca;
      cursor: pointer;
      font-size: 0.85rem;
      transition: 0.2s ease;
    }

    .btn-delete:hover {
      background: #f97373;
      color: #1f2937;
    }

    /* ====== GOALS SECTION (C) ====== */

    .goals-grid {
      display: flex;
      gap: 1rem;
    }

    .goal-card {
      flex: 1;
      background: #2a3056;
      border-radius: 12px;
      padding: 0.9rem 1rem;
      transition: flex 0.35s ease, transform 0.35s ease, opacity 0.35s ease;
    }

    .goals-grid:hover .goal-card {
      flex: 0.7;
      opacity: 0.75;
    }

    .goal-card:hover {
      flex: 2 !important;
      opacity: 1 !important;
    }

    .goal-header {
      font-size: 0.95rem;
      color: #c3c6df;
      margin-bottom: 0.35rem;
    }

    .goal-bar {
      width: 100%;
      background: #1e233d;
      border-radius: 999px;
      overflow: hidden;
      height: 10px;
      margin-bottom: 0.4rem;
    }


    .funny-text {
      margin-top: 8px;
      font-size: 0.9rem;
      color: #ffd28a;
      text-align: center;
      opacity: 0.85;
      font-style: italic;
      transition: opacity 0.25s ease;
    }

    .goal-card .funny-text {
      opacity: 0;
    }

    .goal-card:hover .funny-text {
      opacity: 1;
    }

    .goal-bar-inner {
      height: 100%;
      width: 0%;
      background: linear-gradient(90deg, #ffb347, #ffd28a);
      transition: width 0.6s ease-out;
    }

    .goal-text {
      font-size: 0.85rem;
      color: #e5e7eb;
    }

    .goal-text span.percent {
      color: #ffb347;
      font-weight: bold;
      margin-left: 0.2rem;
    }

    /* Maneuver list wrapper */
    .maneuver-list {
      position: absolute;
      top: 55px;
      left: 0;
      width: 100%;
      padding: 0.5rem 1rem;
      background: #2a3056;
      border-radius: 12px;
      display: none;
      /* hide by default */
      z-index: 20;
    }

    /* Show only when goal-card is hovered */
    .goal-card:hover .maneuver-list {
      display: block;
    }

    /* Ensure card does NOT expand height */
    .goal-card {
      position: relative;
      overflow: visible;
    }

    /* Flip card container */
    .maneuver-flip {
      position: relative;
      perspective: 1200px;
      height: 150px;
      /* not huge */
      overflow: visible;
    }

    /* Inner rotating wrapper */
    .flip-inner {
      position: relative;
      width: 100%;
      height: 100%;
      transition: transform 0.6s ease;
      transform-style: preserve-3d;
    }

    /* Flip on hover */
    .maneuver-flip:hover .flip-inner {
      transform: rotateY(180deg);
    }

    /* Front + Back base styles */
    .flip-front,
    .flip-back {
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 12px;
      backface-visibility: hidden;
      padding: 1rem;
    }

    /* FRONT */
    .flip-front {
      background: #2a3056;
    }

    /* BACK (better background) */
    .flip-back {
      background: #2a3056;
      transform: rotateY(180deg);
      color: white;
      overflow-y: auto;
      padding-top: 0.5rem;
    }

    /* Maneuver list styling */
    #maneuverList {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    #maneuverList li {
      padding: 4px 0;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    #maneuverList li.done {
      color: #bbf7d0;
      /* green */
    }

    #maneuverList li.not-done {
      color: #fecaca;
      /* red */
    }

    .man-title {
      margin: 0 0 5px 0;
      font-size: 0.95rem;
      font-weight: bold;
      text-align: center;
    }



    /* ====== SUMMARY STATS (B) ====== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
    }

    .stat-box {
      background: #2a3056;
      border-radius: 12px;
      padding: 0.9rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
    }

    .stat-label {
      font-size: 0.9rem;
      color: #c3c6df;
    }

    .stat-value {
      font-size: 1.2rem;
      font-weight: bold;
      color: #ffb347;
    }

    /* ====== CHARTS SECTION (E) ====== */
    .charts-grid {
      display: flex;
      flex-direction: column;
      gap: 2rem;
      /* spacing between charts */
    }

    .chart-card {
      width: 100%;
      background: #2a3056;
      border-radius: 12px;
      padding: 1.5rem;
      height: 380px;

      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      /* center chart */
    }

    canvas {
      width: 60% !important;
      /* smaller but clean */
      height: 260px !important;
    }


    /* ====== DELETE MODAL ====== */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.9);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1400;
      opacity: 0;
      visibility: hidden;
      transition: 0.25s ease;
    }

    .modal-overlay.visible {
      opacity: 1;
      visibility: visible;
    }

    .modal-content {
      background: #333a6e;
      border-radius: 14px;
      padding: 1.4rem 1.6rem;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 14px 32px rgba(0, 0, 0, 0.5);
    }

    .modal-title {
      font-size: 1.1rem;
      font-weight: bold;
      color: #ffb347;
      margin-bottom: 0.6rem;
    }

    .modal-text {
      font-size: 0.95rem;
      color: #e5e7eb;
      margin-bottom: 1.1rem;
      line-height: 1.5;
    }

    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.6rem;
    }

    .btn-modal-cancel,
    .btn-modal-confirm {
      padding: 0.55rem 1rem;
      border-radius: 999px;
      border: 1px solid transparent;
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .btn-modal-cancel {
      background: #1f2937;
      color: #e5e7eb;
      border-color: #4b5563;
    }

    .btn-modal-cancel:hover {
      background: #111827;
    }

    .btn-modal-confirm {
      background: #f97373;
      color: #111827;
      border-color: #fecaca;
    }

    .btn-modal-confirm:hover {
      background: #fecaca;
    }

    @media (max-width: 480px) {
      .topbar-title {
        font-size: 1.1rem;
      }

      .topbar-nav {
        gap: 0.6rem;
      }

      .topbar-link {
        font-size: 0.8rem;
        padding: 0.25rem 0.6rem;
      }
    }


    .export-btn,
    .export-btn span,
    .export-btn:hover,
    .export-btn:hover span,
    .export-btn:focus,
    .export-btn:active {
      border-radius: 12px !important;
    }


    .export-btn {
      --accent: #ffb347;
      --accent-dark: #d68a2b;
      --overlay: rgba(255, 179, 71, 0.25);

      border: none;
      display: inline-block;
      position: relative;
      padding: 0.7em 2.4em;
      font-size: 18px;
      background: transparent;
      cursor: pointer;
      user-select: none;
      overflow: hidden;
      color: var(--accent);
      outline: none;
      box-shadow: none;
      z-index: 1;
      font-family: inherit;
      font-weight: 600;
    }

    .export-btn:focus {
      outline: none !important;
      box-shadow: none !important;
    }

    .export-btn span {
      position: absolute;
      inset: 0;
      border: 3px solid var(--accent);
      border-radius: 8px;
      z-index: -1;
      box-shadow: inset 0 0 0 2px var(--accent);


    }

    .export-btn span::before {
      content: "";
      position: absolute;
      width: 7.8%;
      height: 500%;
      background: var(--overlay);
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-60deg);
      transition: 0.35s ease;
      backface-visibility: hidden;
      transform-style: preserve-3d;
    }

    .export-btn:hover span::before {
      transform: translate(-50%, -50%) rotate(-90deg);
      width: 100%;
      background: var(--accent);
    }

    .export-btn:hover {
      color: #1d1f33;
    }

    .export-btn:active span::before {
      background: var(--accent-dark);
    }

    .export-wrapper {
      width: 100%;
      display: flex;
      justify-content: center;
      /* centers horizontally */
      margin: 20px 0;
    }
  </style>
</head>

<body>

  <!-- ====== HEADER (A) ====== -->
  <header class="topbar">
    <div class="topbar-title">DriveX – Dashboard</div>
    <nav class="topbar-nav">
      <a href="index.php" class="topbar-link">Main Page</a>
      <a href="WebForm.php" class="topbar-link">Form</a>
    </nav>
  </header>

  <!-- MESSAGE BAR -->
  <div id="messageBar"></div>

  <!-- DELETE CONFIRM MODAL -->
  <div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-title">Delete Experience</div>
      <div id="deleteModalText" class="modal-text"></div>
      <div class="modal-actions">
        <button type="button" class="btn-modal-cancel" id="cancelDeleteBtn">Cancel</button>
        <button type="button" class="btn-modal-confirm" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>

  <div id="editModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-title">Edit Experience</div>

      <form id="editForm">

        <input type="hidden" id="editExpID">

        <label>Date:</label>
        <input type="date" id="editDate" class="modal-input"><br>

        <label>Start:</label>
        <input type="time" id="editStart" class="modal-input"><br>

        <label>End:</label>
        <input type="time" id="editEnd" class="modal-input"><br>

        <label>Kilometers:</label>
        <input type="number" id="editKm" class="modal-input"><br>

        <label>Weather:</label>
        <select id="editWeather" class="modal-input"></select><br>

        <label>Surface:</label>
        <select id="editSurface" class="modal-input"></select><br>

        <label>Traffic:</label>
        <select id="editTraffic" class="modal-input"></select><br>

        <label>Maneuvers:</label>
        <select id="editMans" class="modal-input" multiple></select><br>

      </form>

      <div class="modal-actions">
        <button class="btn-modal-cancel" id="editCancelBtn">Cancel</button>
        <button class="btn-modal-confirm" id="editSaveBtn">Save</button>
      </div>
    </div>
  </div>

  <!-- MAIN CONTENT -->
  <div class="page-content">

    <!-- D) TABLE OF EXPERIENCES -->
    <section class="section-card" id="section-table">
      <h2 class="section-title">All Driving Experiences</h2>
      <table id="experienceTable" class="display" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Time</th>
            <th>Kilometers</th>
            <th>Weather</th>
            <th>Surface</th>
            <th>Traffic</th>
            <th>Maneuvers</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Filled by JS -->
        </tbody>
      </table>
      <div class="export-wrapper">
        <button id="exportCSV" class="export-btn">
          📥 Export CSV
          <span></span>
        </button>
      </div>

    </section>

    <!-- C) GOALS / PROGRESS BARS -->
    <section class="section-card" id="section-goals">
      <h2 class="section-title">License Progress</h2>
      <div class="goals-grid">
        <div class="goal-card" id="kmCard">
          <div class="goal-header">Total Kilometers (Goal: 3000 km)</div>
          <div class="goal-bar">
            <div id="kmBar" class="goal-bar-inner"></div>
          </div>
          <div class="goal-text">
            <span id="kmLabel">0 / 3000 km</span>
            <span id="kmPercent" class="percent">(0%)</span>
          </div>
          <div class="funny-text kmFunny"></div>

        </div>

        <div class="goal-card" id="hoursCard">
          <div class="goal-header">Total Driving Hours (Goal: 120 h)</div>
          <div class="goal-bar">
            <div id="hoursBar" class="goal-bar-inner"></div>
          </div>
          <div class="goal-text">
            <span id="hoursLabel">0 / 120 h</span>
            <span id="hoursPercent" class="percent">(0%)</span>
          </div>
          <div class="funny-text hoursFunny"></div>

        </div>

        <div class="goal-card maneuver-flip">
          <div class="flip-inner">

            <!-- FRONT SIDE -->
            <div class="flip-front">
              <div class="goal-header">Maneuvers Mastered</div>
              <div class="goal-bar">
                <div id="manBar" class="goal-bar-inner"></div>
              </div>
              <div class="goal-text">
                <span id="manLabel">0 / 12</span>
                <span id="manPercent" class="percent">(0%)</span>
              </div>
            </div>

            <!-- BACK SIDE (dynamic list generated by JS) -->
            <div class="flip-back">
              <h3 class="man-title">Maneuvers</h3>
              <ul id="maneuverList"></ul>
            </div>

          </div>
        </div>
      </div>
    </section>

    <!-- B) SUMMARY STATS -->
    <section class="section-card" id="section-stats">
      <h2 class="section-title">Summary</h2>
      <div class="stats-grid">
        <div class="stat-box">
          <div class="stat-label">Total Drives</div>
          <div id="statTotalDrives" class="stat-value">0</div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Total Hours</div>
          <div id="statTotalHours" class="stat-value">0</div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Total Distance (km)</div>
          <div id="statTotalKm" class="stat-value">0</div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Maneuvers Logged</div>
          <div id="statTotalMan" class="stat-value">0</div>
        </div>
      </div>
    </section>

    <!-- E) CHARTS -->
    <section class="section-card" id="section-charts">
      <h2 class="section-title">Conditions Overview</h2>
      ONLY its internal grid with this upgraded version:

      <div class="charts-grid">

        <div class="chart-card">
          <div class="chart-card-title">Weather Distribution</div>
          <canvas id="weatherChart"></canvas>
        </div>

        <div class="chart-card">
          <div class="chart-card-title">Surface Distribution</div>
          <canvas id="surfaceChart"></canvas>
        </div>

        <div class="chart-card">
          <div class="chart-card-title">Traffic Distribution</div>
          <canvas id="trafficChart"></canvas>
        </div>

        <!-- NEW MANEUVERS DISTRIBUTION -->
        <div class="chart-card">
          <div class="chart-card-title">Maneuvers Distribution</div>
          <canvas id="maneuverChart"></canvas>
        </div>

        <!-- NEW MONTHLY ACTIVITY CHART -->
        <div class="chart-card">
          <div class="chart-card-title">Monthly Driving Activity</div>
          <canvas id="monthChart"></canvas>
        </div>

      </div>
    </section>

  </div>

  <script>
    // ====== GLOBAL STATE ======
    let experiences = [];
    let weatherMap = {};
    let surfaceMap = {};
    let trafficMap = {};
    let maneuverMap = {};

    let dataTable = null;
    let weatherChart = null;
    let surfaceChart = null;
    let trafficChart = null;
    let maneuverChart = null;
    let monthChart = null;
    let deleteTargetId = null;

    if (window.Chart && Chart.defaults && Chart.defaults.global) {
      Chart.defaults.global.defaultFontColor = "#ffffff";
      Chart.defaults.global.defaultFontSize = 12;
    }

    // ====== MESSAGE BAR ======
    function showMessage(text, hslColor) {
      const bar = document.getElementById("messageBar");
      bar.textContent = text;
      bar.style.backgroundColor = `hsl(${hslColor})`;
      bar.classList.add("visible");
      setTimeout(() => {
        bar.classList.remove("visible");
      }, 3500);
    }

    // ====== ICON HELPERS ======
    function iconForWeather(desc) {
      if (!desc) return "🌡️";
      const d = desc.toLowerCase();
      if (d.includes("sun")) return "☀️";
      if (d.includes("cloud")) return "☁️";
      if (d.includes("wind")) return "🌬️";
      if (d.includes("snow")) return "❄️";
      if (d.includes("rain")) return "🌧️";
      if (d.includes("fog")) return "🌫️";
      return "🌡️";
    }

    function iconForSurface(desc) {
      if (!desc) return "🛣️";
      const d = desc.toLowerCase();
      if (d.includes("dry")) return "🛣️";
      if (d.includes("wet")) return "💧";
      if (d.includes("icy")) return "🧊";
      if (d.includes("snow")) return "❄️";
      return "🛣️";
    }

    function iconForTraffic(desc) {
      if (!desc) return "🚦";
      const d = desc.toLowerCase();
      if (d.includes("low")) return "🟢";
      if (d.includes("moderate")) return "🟡";
      if (d.includes("high")) return "🔴";
      return "🚦";
    }

    function iconLabelHTML(icon, text) {
      return `
        <span class="icon-label">
          <span class="icon">${icon}</span>
          <span class="text">${text}</span>
        </span>
      `;
    }

    // ====== LOAD STATIC DATA & EXPERIENCES ======
    async function loadDashboard() {
      try {
        const [staticRes, expRes] = await Promise.all([
          fetch("https://shahin.alwaysdata.net/webproject/routes/get_static_data.php"),
          fetch("https://shahin.alwaysdata.net/webproject/routes/get_experiences.php")
        ]);

        const staticData = await staticRes.json();
        const expData = await expRes.json();

        if (staticData.status !== "success") {
          showMessage("Error loading static tables", "0, 80%, 50%");
          return;
        }

        // Build maps
        weatherMap = {};
        surfaceMap = {};
        trafficMap = {};
        maneuverMap = {};

        staticData.weather.forEach(w => {
          weatherMap[w.weatherID] = w.weatherDescription;
        });
        staticData.surface.forEach(s => {
          surfaceMap[s.surfaceID] = s.surfaceDescription;
        });
        staticData.traffic.forEach(t => {
          trafficMap[t.trafficID] = t.trafficDescription;
        });
        staticData.maneuvers.forEach(m => {
          maneuverMap[m.maneuverID] = m.maneuverDescription;
        });

        function fillEditDropdowns() {
          const wSel = document.getElementById("editWeather");
          const sSel = document.getElementById("editSurface");
          const tSel = document.getElementById("editTraffic");
          const mSel = document.getElementById("editMans");

          if (!wSel || !sSel || !tSel || !mSel) return;

          wSel.innerHTML = "";
          sSel.innerHTML = "";
          tSel.innerHTML = "";
          mSel.innerHTML = "";

          // WEATHER OPTIONS
          Object.keys(weatherMap).forEach(id => {
            const opt = document.createElement("option");
            opt.value = id;
            opt.textContent = weatherMap[id];
            wSel.appendChild(opt);
          });

          // SURFACE OPTIONS
          Object.keys(surfaceMap).forEach(id => {
            const opt = document.createElement("option");
            opt.value = id;
            opt.textContent = surfaceMap[id];
            sSel.appendChild(opt);
          });

          // TRAFFIC OPTIONS
          Object.keys(trafficMap).forEach(id => {
            const opt = document.createElement("option");
            opt.value = id;
            opt.textContent = trafficMap[id];
            tSel.appendChild(opt);
          });

          // MANEUVER OPTIONS (base list)
          Object.keys(maneuverMap).forEach(id => {
            const opt = document.createElement("option");
            opt.value = id;
            opt.textContent = maneuverMap[id];
            mSel.appendChild(opt);
          });
        }

        // Call it immediately after maps are built
        fillEditDropdowns();

        experiences = Array.isArray(expData) ? expData : (expData.records || []);
        if (!Array.isArray(experiences)) experiences = [];

        initTable(experiences);
        renderStatsAndGoals();
        renderCharts();

      } catch (err) {
        showMessage("Connection error while loading dashboard", "0, 80%, 50%");
        console.error(err);
      }
    }

    function initTable(experiences) {
      if (dataTable) {
        dataTable.destroy();
        $("#experienceTable tbody").empty();
      }

      dataTable = $('#experienceTable').DataTable({
        data: experiences,
        pageLength: 5,
        responsive: true,
        destroy: true,

        columns: [{
            data: "expID",
            title: "ID"
          },
          {
            data: "date",
            title: "Date"
          },
          {
            data: "startTime",
            title: "Start"
          },
          {
            data: "endTime",
            title: "End"
          },
          {
            data: "kilometers",
            title: "KM"
          },

          {
            data: "weatherID",
            title: "Weather",
            render: id => weatherMap[id] || ("Weather " + id)
          },

          {
            data: "surfaceID",
            title: "Surface",
            render: id => surfaceMap[id] || ("Surface " + id)
          },

          {
            data: "trafficID",
            title: "Traffic",
            render: id => trafficMap[id] || ("Traffic " + id)
          },

          {
            data: "maneuvers",
            title: "Maneuvers",
            render: function(mans) {
              if (!Array.isArray(mans) || mans.length === 0) return "None";
              return mans.map(id => maneuverMap[id] || ("M" + id)).join(", ");
            }
          },

          {
            data: "expID",
            title: "Action",
            render: function(id) {

              return `
                        <button class="btn-edit" data-exp-id="${id}">Edit</button>
                        <button class="btn-delete" data-exp-id="${id}">
                            Delete
                        </button>
                    `;
            }
          }
        ]
      });
    }



    // ====== HOURS DIFFERENCE ======
    function getHours(start, end) {
      if (!start || !end) return 0;
      const s = new Date("2000-01-01T" + start);
      const e = new Date("2000-01-01T" + end);
      const diffMs = e - s;
      return diffMs / 1000 / 60 / 60;
    }

    function renderManeuverList(uniqueManeuvers) {
      const list = document.getElementById("maneuverList");
      list.innerHTML = ""; // clear

      // Get all maneuver names from DB
      const allMans = Object.keys(maneuverMap);

      allMans.forEach(id => {
        const name = maneuverMap[id];

        const li = document.createElement("li");

        if (uniqueManeuvers.has(parseInt(id))) {
          li.classList.add("done");
          li.innerHTML = `✔️ ${name}`;
        } else {
          li.classList.add("not-done");
          li.innerHTML = `✖️ ${name}`;
        }

        list.appendChild(li);
      });
    }



    // ====== STATS + GOALS (C + B) ======
    function renderStatsAndGoals() {
      const totalDrives = experiences.length;

      let totalKm = 0;
      let totalHours = 0;
      const uniqueManeuvers = new Set(); // only declared ONCE

      // Collect data
      experiences.forEach(exp => {
        totalKm += parseFloat(exp.kilometers) || 0;
        totalHours += getHours(exp.startTime, exp.endTime);

        const manArr = Array.isArray(exp.maneuvers) ? exp.maneuvers : [];
        manArr.forEach(id => uniqueManeuvers.add(id));
      });

      const maneuversMastered = uniqueManeuvers.size;

      // Update summary stats
      document.getElementById("statTotalDrives").textContent = totalDrives;
      document.getElementById("statTotalHours").textContent = totalHours.toFixed(1);
      document.getElementById("statTotalKm").textContent = totalKm.toFixed(1);
      document.getElementById("statTotalMan").textContent = maneuversMastered;

      // Goals:
      const kmGoal = 3000;
      const hoursGoal = 120;
      const manGoal = Object.keys(maneuverMap).length; // dynamic from DB

      const kmPercent = (totalKm / kmGoal) * 100;
      const hoursPercent = (totalHours / hoursGoal) * 100;
      const manPercent = (maneuversMastered / manGoal) * 100;

      // Bars
      document.getElementById("kmBar").style.width = kmPercent.toFixed(1) + "%";
      document.getElementById("hoursBar").style.width = hoursPercent.toFixed(1) + "%";
      document.getElementById("manBar").style.width = manPercent.toFixed(1) + "%";

      // Labels
      document.getElementById("kmLabel").textContent = `${totalKm.toFixed(1)} / ${kmGoal} km`;
      document.getElementById("hoursLabel").textContent = `${totalHours.toFixed(1)} / ${hoursGoal} h`;
      document.getElementById("manLabel").textContent = `${maneuversMastered} / ${manGoal}`;

      document.getElementById("kmPercent").textContent = `(${kmPercent.toFixed(1)}%)`;
      document.getElementById("hoursPercent").textContent = `(${hoursPercent.toFixed(1)}%)`;
      document.getElementById("manPercent").textContent = `(${manPercent.toFixed(1)}%)`;

      // === NEW: Render maneuver list dynamically ===
      renderManeuverList(uniqueManeuvers);
      const funnyLines = [
        "Vrooooom! 🚗💨",
        "Burning asphalt, driver! 🔥",
        "License speedrun in progress 😎",
        "Your instructor would high-five you 🙌",
        "You're cooking the road! 👨‍🍳",
        "Certified wheel wizard 🪄",
        "Highway domination loading… ⚡",
        "Driving XP +10 🎮"
      ];

      function randomFunny() {
        return funnyLines[Math.floor(Math.random() * funnyLines.length)];
      }

      // KM hover → new funny line
document.getElementById("kmCard").addEventListener("mouseenter", () => {
  document.querySelector(".kmFunny").textContent = randomFunny();
});

// Hours hover → new funny line
document.getElementById("hoursCard").addEventListener("mouseenter", () => {
  document.querySelector(".hoursFunny").textContent = randomFunny();
});
    }



    // ====== CHARTS (E) ======
    function renderCharts() {

      // ========== COUNTING ==========
      const weatherCounts = {};
      const surfaceCounts = {};
      const trafficCounts = {};
      const maneuverCounts = {};
      const dailyCounts = {};

      experiences.forEach(exp => {
        weatherCounts[exp.weatherID] = (weatherCounts[exp.weatherID] || 0) + 1;
        surfaceCounts[exp.surfaceID] = (surfaceCounts[exp.surfaceID] || 0) + 1;
        trafficCounts[exp.trafficID] = (trafficCounts[exp.trafficID] || 0) + 1;

        (exp.maneuvers || []).forEach(id => {
          maneuverCounts[id] = (maneuverCounts[id] || 0) + 1;
        });

        const day = exp.date;
        dailyCounts[day] = (dailyCounts[day] || 0) + 1;
      });

      // Make readable donut sizes + clear labels
      const pieOptions = {
        cutoutPercentage: 45,
        legend: {
          position: "bottom",
          labels: {
            fontColor: "#fff",
            fontSize: 14,
            padding: 12
          }
        },
        animation: {
          animateScale: true
        }
      };

      const destroy = chart => {
        if (chart) chart.destroy();
      };
      destroy(weatherChart);
      destroy(surfaceChart);
      destroy(trafficChart);
      destroy(maneuverChart);
      destroy(monthChart);

      // ========= WEATHER CHART =========
      weatherChart = new Chart(
        document.getElementById("weatherChart").getContext("2d"), {
          type: "doughnut",
          data: {
            labels: Object.keys(weatherCounts).map(id => weatherMap[id]),
            datasets: [{
              data: Object.values(weatherCounts),
              backgroundColor: ["#ffadad", "#ffd6a5", "#fdffb6", "#caffbf", "#9bf6ff", "#a0c4ff"]
            }]
          },
          options: pieOptions
        }
      );

      // ========= SURFACE CHART =========
      surfaceChart = new Chart(
        document.getElementById("surfaceChart").getContext("2d"), {
          type: "doughnut",
          data: {
            labels: Object.keys(surfaceCounts).map(id => surfaceMap[id]),
            datasets: [{
              data: Object.values(surfaceCounts),
              backgroundColor: ["#bde0fe", "#a2d2ff", "#ffc8dd", "#ffafcc"]
            }]
          },
          options: pieOptions
        }
      );

      // ========= TRAFFIC CHART =========
      trafficChart = new Chart(
        document.getElementById("trafficChart").getContext("2d"), {
          type: "doughnut",
          data: {
            labels: Object.keys(trafficCounts).map(id => trafficMap[id]),
            datasets: [{
              data: Object.values(trafficCounts),
              backgroundColor: ["#bbf7d0", "#fde68a", "#fecaca"]
            }]
          },
          options: pieOptions
        }
      );

      // ========= MANEUVERS PIE =========
      maneuverChart = new Chart(
        document.getElementById("maneuverChart").getContext("2d"), {
          type: "doughnut",
          data: {
            labels: Object.keys(maneuverCounts).map(id => maneuverMap[id]),
            datasets: [{
              data: Object.values(maneuverCounts),
              backgroundColor: [
                "#ffadad", "#ffd6a5", "#fdffb6", "#caffbf",
                "#9bf6ff", "#a0c4ff", "#bdb2ff", "#ffc6ff"
              ]
            }]
          },
          options: pieOptions
        }
      );

      // ========= MONTHLY LINE CHART =========
      const sortedDays = Object.keys(dailyCounts).sort();
      monthChart = new Chart(
        document.getElementById("monthChart").getContext("2d"), {
          type: "line",
          data: {
            labels: sortedDays,
            datasets: [{
              label: "Drives per Day",
              data: sortedDays.map(d => dailyCounts[d]),
              borderColor: "#ffb347",
              backgroundColor: "rgba(255,179,71,0.25)",
              borderWidth: 3,
              pointRadius: 6,
              pointBackgroundColor: "#ffb347",
              tension: 0.35
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  precision: 0,
                  fontColor: "#fff"
                }
              }],
              xAxes: [{
                ticks: {
                  fontColor: "#fff"
                }
              }]
            },
            legend: {
              labels: {
                fontColor: "#fff"
              }
            }
          }
        }
      );
    }


    // ====== DELETE MODAL HANDLING ======
    function openDeleteModal(expId) {
      deleteTargetId = expId;
      const modal = document.getElementById("deleteModal");
      const txt = document.getElementById("deleteModalText");
      txt.textContent =
        `Are you sure you want to delete experience #${expId}? ` +
        `This will also remove all maneuvers linked to it.`;
      modal.classList.add("visible");
    }

    function closeDeleteModal() {
      const modal = document.getElementById("deleteModal");
      modal.classList.remove("visible");
      deleteTargetId = null;
    }

    async function performDelete() {
      if (!deleteTargetId) return;

      try {
        const res = await fetch("https://shahin.alwaysdata.net/webproject/routes/delete_experience.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            expID: deleteTargetId
          })
        });

        const data = await res.json();

        if (data.status === "success") {
          showMessage(`Experience #${deleteTargetId} deleted`, "120, 80%, 35%");
          experiences = experiences.filter(e => e.expID !== deleteTargetId);
          initTable(experiences);

          renderStatsAndGoals();
          renderCharts();
        } else {
          showMessage(data.msg || "Error deleting experience", "0, 80%, 50%");
        }

      } catch (err) {
        console.error(err);
        showMessage("Connection error while deleting", "0, 80%, 50%");
      } finally {
        closeDeleteModal();
      }
    }

    function openEditModal(expId) {
      const exp = experiences.find(x => x.expID === expId);
      if (!exp) return;

      document.getElementById("editExpID").value = exp.expID;
      document.getElementById("editDate").value = exp.date;
      document.getElementById("editStart").value = exp.startTime;
      document.getElementById("editEnd").value = exp.endTime;
      document.getElementById("editKm").value = exp.kilometers;
      document.getElementById("editWeather").value = exp.weatherID;
      document.getElementById("editSurface").value = exp.surfaceID;
      document.getElementById("editTraffic").value = exp.trafficID;

      const mansSelect = document.getElementById("editMans");
      mansSelect.innerHTML = "";

      Object.keys(maneuverMap).forEach(id => {
        const opt = document.createElement("option");
        opt.value = id;
        opt.textContent = maneuverMap[id];
        if (exp.maneuvers.includes(parseInt(id))) opt.selected = true;
        mansSelect.appendChild(opt);
      });

      document.getElementById("editModal").classList.add("visible");
    }

    document.getElementById("editSaveBtn").addEventListener("click", async () => {
      const expID = parseInt(document.getElementById("editExpID").value);

      const payload = {
        expID: expID,
        date: document.getElementById("editDate").value,
        startTime: document.getElementById("editStart").value,
        endTime: document.getElementById("editEnd").value,
        kilometers: parseFloat(document.getElementById("editKm").value),
        weatherID: parseInt(document.getElementById("editWeather").value),
        surfaceID: parseInt(document.getElementById("editSurface").value),
        trafficID: parseInt(document.getElementById("editTraffic").value),
        maneuvers: Array.from(
          document.getElementById("editMans").selectedOptions
        ).map(o => parseInt(o.value))
      };

      const res = await fetch("https://shahin.alwaysdata.net/webproject/routes/edit_experience.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
      });

      const data = await res.json();

      if (data.status === "success") {
        showMessage("Experience updated", "120,80%,35%");
        closeEditModal();
        loadDashboard(); // refresh everything
      } else {
        showMessage("Error updating", "0,80%,50%");
      }
    });

    function closeEditModal() {
      document.getElementById("editModal").classList.remove("visible");
    }
    document.getElementById("editCancelBtn").onclick = closeEditModal;

    document.getElementById("exportCSV").addEventListener("click", () => {
      let csv = "ID,Date,Start,End,Kilometers,Weather,Surface,Traffic,Maneuvers\n";

      experiences.forEach(exp => {
        const mans = (exp.maneuvers || []).map(id => maneuverMap[id]).join(";");
        csv += [
          exp.expID,
          exp.date,
          exp.startTime,
          exp.endTime,
          exp.kilometers,
          weatherMap[exp.weatherID],
          surfaceMap[exp.surfaceID],
          trafficMap[exp.trafficID],
          mans
        ].join(",") + "\n";
      });

      const blob = new Blob([csv], {
        type: "text/csv"
      });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = "driving_experiences.csv";
      link.click();
    });


    // ====== EVENT LISTENERS ======
    document.addEventListener("click", (e) => {
      if (e.target.matches(".btn-delete")) {
        const expId = parseInt(e.target.getAttribute("data-exp-id"), 10);
        openDeleteModal(expId);
      }
    });
    document.addEventListener("click", (e) => {
      if (e.target.matches(".btn-edit")) {
        const expId = parseInt(e.target.getAttribute("data-exp-id"), 10);
        openEditModal(expId);
      }
    });

    document.getElementById("cancelDeleteBtn").addEventListener("click", () => {
      closeDeleteModal();
    });

    document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
      performDelete();
    });

    // Close modal on ESC
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        closeDeleteModal();
      }
    });

    // INIT
    window.onload = () => {
      loadDashboard();
    };
  </script>
</body>

</html>