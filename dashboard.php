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

    html, body {
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
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1rem;
    }

    .goal-card {
      background: #2a3056;
      border-radius: 12px;
      padding: 0.9rem 1rem;
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
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1rem;
    }

    .chart-card {
      background: #2a3056;
      border-radius: 12px;
      padding: 0.9rem 1rem;
    }

    .chart-card-title {
      font-size: 0.95rem;
      color: #ffb347;
      margin-bottom: 0.4rem;
    }

    canvas {
      width: 100% !important;
      height: 220px !important;
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
      box-shadow: 0 14px 32px rgba(0,0,0,0.5);
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
  </style>
</head>
<body>

  <!-- ====== HEADER (A) ====== -->
  <header class="topbar">
    <div class="topbar-title">Driving Experience – Dashboard</div>
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
    </section>

    <!-- C) GOALS / PROGRESS BARS -->
    <section class="section-card" id="section-goals">
      <h2 class="section-title">License Progress</h2>
      <div class="goals-grid">
        <div class="goal-card">
          <div class="goal-header">Total Kilometers (Goal: 3000 km)</div>
          <div class="goal-bar">
            <div id="kmBar" class="goal-bar-inner"></div>
          </div>
          <div class="goal-text">
            <span id="kmLabel">0 / 3000 km</span>
            <span id="kmPercent" class="percent">(0%)</span>
          </div>
        </div>

        <div class="goal-card">
          <div class="goal-header">Total Driving Hours (Goal: 120 h)</div>
          <div class="goal-bar">
            <div id="hoursBar" class="goal-bar-inner"></div>
          </div>
          <div class="goal-text">
            <span id="hoursLabel">0 / 120 h</span>
            <span id="hoursPercent" class="percent">(0%)</span>
          </div>
        </div>

        <div class="goal-card">
          <div class="goal-header">Maneuvers Mastered (Goal: 12)</div>
          <div class="goal-bar">
            <div id="manBar" class="goal-bar-inner"></div>
          </div>
          <div class="goal-text">
            <span id="manLabel">0 / 12</span>
            <span id="manPercent" class="percent">(0%)</span>
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
          fetch("https://shahin.alwaysdata.net/webproject/get_static_data.php"),
          fetch("https://shahin.alwaysdata.net/webproject/get_experiences.php")
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

        columns: [
            { data: "expID", title: "ID" },
            { data: "date", title: "Date" },
            { data: "startTime", title: "Start" },
            { data: "endTime", title: "End" },
            { data: "kilometers", title: "KM" },

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

    // ====== STATS + GOALS (C + B) ======
    function renderStatsAndGoals() {
      const totalDrives = experiences.length;

      let totalKm = 0;
      let totalHours = 0;
      const uniqueManeuvers = new Set();

      experiences.forEach(exp => {
        totalKm += parseFloat(exp.kilometers) || 0;
        totalHours += getHours(exp.startTime, exp.endTime);
        const manArr = Array.isArray(exp.maneuvers) ? exp.maneuvers : [];
        manArr.forEach(id => uniqueManeuvers.add(id));
      });

      const maneuversMastered = uniqueManeuvers.size;

      // Summary stats
      document.getElementById("statTotalDrives").textContent = totalDrives;
      document.getElementById("statTotalHours").textContent = totalHours.toFixed(1);
      document.getElementById("statTotalKm").textContent = totalKm.toFixed(1);
      document.getElementById("statTotalMan").textContent = maneuversMastered;

      // Goals
      const kmGoal = 3000;
      const hoursGoal = 120;
      const manGoal = 12;

      const kmPercent = kmGoal > 0 ? Math.min(100, (totalKm / kmGoal) * 100) : 0;
      const hoursPercent = hoursGoal > 0 ? Math.min(100, (totalHours / hoursGoal) * 100) : 0;
      const manPercent = manGoal > 0 ? Math.min(100, (maneuversMastered / manGoal) * 100) : 0;

      document.getElementById("kmBar").style.width = kmPercent.toFixed(1) + "%";
      document.getElementById("hoursBar").style.width = hoursPercent.toFixed(1) + "%";
      document.getElementById("manBar").style.width = manPercent.toFixed(1) + "%";

      document.getElementById("kmLabel").textContent = `${totalKm.toFixed(1)} / ${kmGoal} km`;
      document.getElementById("hoursLabel").textContent = `${totalHours.toFixed(1)} / ${hoursGoal} h`;
      document.getElementById("manLabel").textContent = `${maneuversMastered} / ${manGoal}`;

      document.getElementById("kmPercent").textContent = `(${kmPercent.toFixed(1)}%)`;
      document.getElementById("hoursPercent").textContent = `(${hoursPercent.toFixed(1)}%)`;
      document.getElementById("manPercent").textContent = `(${manPercent.toFixed(1)}%)`;
    }

    // ====== CHARTS (E) ======
    function renderCharts() {
      const weatherCounts = {};
      const surfaceCounts = {};
      const trafficCounts = {};

      experiences.forEach(exp => {
        const wID = exp.weatherID;
        const sID = exp.surfaceID;
        const tID = exp.trafficID;

        weatherCounts[wID] = (weatherCounts[wID] || 0) + 1;
        surfaceCounts[sID] = (surfaceCounts[sID] || 0) + 1;
        trafficCounts[tID] = (trafficCounts[tID] || 0) + 1;
      });

      const weatherLabels = Object.keys(weatherCounts).map(id => {
        const n = parseInt(id, 10);
        return weatherMap[n] || ("Weather " + n);
      });
      const weatherData = Object.values(weatherCounts);

      const surfaceLabels = Object.keys(surfaceCounts).map(id => {
        const n = parseInt(id, 10);
        return surfaceMap[n] || ("Surface " + n);
      });
      const surfaceData = Object.values(surfaceCounts);

      const trafficLabels = Object.keys(trafficCounts).map(id => {
        const n = parseInt(id, 10);
        return trafficMap[n] || ("Traffic " + n);
      });
      const trafficData = Object.values(trafficCounts);

      const wCtx = document.getElementById("weatherChart").getContext("2d");
      const sCtx = document.getElementById("surfaceChart").getContext("2d");
      const tCtx = document.getElementById("trafficChart").getContext("2d");

      if (weatherChart) weatherChart.destroy();
      if (surfaceChart) surfaceChart.destroy();
      if (trafficChart) trafficChart.destroy();

      weatherChart = new Chart(wCtx, {
        type: "pie",
        data: {
          labels: weatherLabels,
          datasets: [{
            data: weatherData,
            backgroundColor: ["#fdffb6", "#bdb2ff", "#87CEEB", "#9bf6ff", "#a0c4ff", "#caffbf"]
          }]
        },
        options: {
          title: {
            display: true,
            text: "Weather",
            fontSize: 15
          },
          legend: {
            position: "right"
          }
        }
      });

      surfaceChart = new Chart(sCtx, {
        type: "pie",
        data: {
          labels: surfaceLabels,
          datasets: [{
            data: surfaceData,
            backgroundColor: ["#ffddd2", "#457b9d", "#f1faee", "#a8dadc"]
          }]
        },
        options: {
          title: {
            display: true,
            text: "Surface",
            fontSize: 15
          },
          legend: {
            position: "right"
          }
        }
      });

      trafficChart = new Chart(tCtx, {
        type: "pie",
        data: {
          labels: trafficLabels,
          datasets: [{
            data: trafficData,
            backgroundColor: ["#bbf7d0", "#fde68a", "#fecaca"]
          }]
        },
        options: {
          title: {
            display: true,
            text: "Traffic",
            fontSize: 15
          },
          legend: {
            position: "right"
          }
        }
      });
    }

    // ====== DELETE MODAL HANDLING ======
    function openDeleteModal(expId) {
      deleteTargetId = expId;
      const modal = document.getElementById("deleteModal");
      const txt = document.getElementById("deleteModalText");
      txt.textContent =
        `Are you sure you want to delete experience #${expId}? `
        + `This will also remove all maneuvers linked to it.`;
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
        const res = await fetch("https://shahin.alwaysdata.net/webproject/delete_experience.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ expID: deleteTargetId })
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

    // ====== EVENT LISTENERS ======
    document.addEventListener("click", (e) => {
    if (e.target.matches(".btn-delete")) {
        const expId = parseInt(e.target.getAttribute("data-exp-id"), 10);
        openDeleteModal(expId);
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
