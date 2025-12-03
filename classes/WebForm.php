<?php
require_once __DIR__ . "/includes/includeDB.inc.php";

/* Fetch dropdown data from DB */
$weatherData = [];
$surfaceData = [];
$trafficData = [];
$accidentData = [];

$result = $mysqli->query("SELECT * FROM Weather");
while ($row = $result->fetch_assoc()) $weatherData[] = $row;

$result = $mysqli->query("SELECT * FROM Surface");
while ($row = $result->fetch_assoc()) $surfaceData[] = $row;

$result = $mysqli->query("SELECT * FROM Traffic");
while ($row = $result->fetch_assoc()) $trafficData[] = $row;

$result = $mysqli->query("SELECT * FROM Accidents");
while ($row = $result->fetch_assoc()) $accidentData[] = $row;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Driving Experience</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

  <!-- BACKEND ➜ JS DROPDOWN ARRAYS -->
  <script>
    const Weather = <?= json_encode($weatherData); ?>;
    const Surface = <?= json_encode($surfaceData); ?>;
    const Traffic = <?= json_encode($trafficData); ?>;
    const Accidents = <?= json_encode($accidentData); ?>;
  </script>

  <style>
    /* ====== GLOBAL RESET & BASE ====== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1d1f33, #4e5d9d);
      color: #ffffff;
      display: flex;
      flex-direction: column;
    }

    /* ====== TOP BAR ====== */
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
      max-width: 1100px;
      margin: 90px auto 40px auto;
      /* space for topbar */
      padding: 0 1rem;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    /* ====== FORM CARD ====== */
    .form-card {
      background: #333a6e;
      border-radius: 16px;
      padding: 2rem 2.5rem;
      width: 100%;
      max-width: 460px;
      box-shadow: 0 14px 30px rgba(0, 0, 0, 0.35);
      transform: translateY(10px);
      opacity: 0;
      animation: cardEnter 0.8s ease forwards;
    }

    @keyframes cardEnter {
      0% {
        opacity: 0;
        transform: translateY(25px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: bold;
      color: #ffb347;
      margin-bottom: 1.4rem;
      text-align: center;
    }

    /* ====== FORM ELEMENTS ====== */
    .form-group {
      margin-bottom: 1rem;
    }

    .form-row {
      display: flex;
      gap: 1rem;
    }

    .form-row .form-group {
      flex: 1;
    }

    label {
      display: block;
      margin-bottom: 0.35rem;
      font-size: 0.95rem;
      color: #c3c6df;
    }

    input[type="date"],
    input[type="time"],
    input[type="number"],
    select {
      width: 100%;
      padding: 0.7rem 0.9rem;
      border-radius: 8px;
      border: none;
      outline: none;
      font-size: 0.95rem;
      background: #434a76;
      color: #ffffff;
      box-shadow: 0 3px 7px rgba(0, 0, 0, 0.28);
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    input[type="date"]:focus,
    input[type="time"]:focus,
    input[type="number"]:focus,
    select:focus {
      transform: scale(1.03);
      box-shadow: 0 5px 12px rgba(0, 0, 0, 0.4);
      border: 1px solid #ffb347;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
    }

    /* ====== BUTTONS ====== */
    .form-buttons {
      display: flex;
      justify-content: space-between;
      gap: 0.75rem;
      margin-top: 1.6rem;
    }

    .btn-primary,
    .btn-secondary,
    .btn-search,
    .btn-back {
      cursor: pointer;
      padding: 0.8rem 1rem;
      border-radius: 999px;
      border: 2px solid #ffb347;
      font-size: 0.95rem;
      font-weight: bold;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      background: #333a6e;
      color: #ffb347;
      position: relative;
      overflow: hidden;
      transition: 0.25s ease;
    }

    .btn-primary::before,
    .btn-secondary::before,
    .btn-search::before,
    .btn-back::before {
      content: "";
      position: absolute;
      top: 0;
      left: 50%;
      right: 50%;
      bottom: 0;
      background: #ffb347;
      opacity: 0;
      transition: 0.4s ease;
      z-index: -1;
    }

    .btn-primary:hover,
    .btn-secondary:hover,
    .btn-search:hover,
    .btn-back:hover {
      color: #2d3356;
      transform: translateY(-1px) scale(1.02);
    }

    .btn-primary:hover::before,
    .btn-secondary:hover::before,
    .btn-search:hover::before,
    .btn-back:hover::before {
      left: 0;
      right: 0;
      opacity: 1;
    }

    /* Different color emphasis (if needed later) */
    .btn-primary {
      background: #ffb347;
      color: #2d3356;
    }

    .btn-primary::before {
      background: #ffd28a;
    }

    /* ====== REVIEW PANEL (OVERLAY) ====== */
    .review-panel {
      position: fixed;
      inset: 0;
      background: rgba(6, 8, 25, 0.88);
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1.5rem;
      opacity: 0;
      visibility: hidden;
      transform: translateY(20px);
      transition: opacity 0.4s ease, transform 0.4s ease, visibility 0.4s;
      z-index: 1200;
    }

    .review-panel.active {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .review-content {
      background: #333a6e;
      border-radius: 18px;
      width: 100%;
      max-width: 650px;
      max-height: 90vh;
      padding: 1.8rem 2rem 1.8rem 2rem;
      box-shadow: 0 16px 30px rgba(0, 0, 0, 0.5);
      overflow-y: auto;
    }

    .review-title {
      font-size: 1.4rem;
      font-weight: bold;
      color: #ffb347;
      margin-bottom: 1rem;
      text-align: center;
    }

    /* ====== REVIEW STATS ====== */
    .review-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 0.8rem;
      margin-bottom: 1.6rem;
    }

    .stat-item {
      background: #2a3056;
      padding: 0.65rem 0.9rem;
      border-radius: 10px;
      font-size: 0.9rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .stat-item span {
      color: #c3c6df;
    }

    .stat-item strong {
      color: #ffb347;
    }

    /* ====== REVIEW SUBTITLES & SEARCH ====== */
    .review-subtitle {
      font-size: 1rem;
      color: #ffb347;
      margin: 0.6rem 0 0.4rem 0;
    }

    .search-input {
      width: 100%;
      padding: 0.7rem 0.9rem;
      border-radius: 10px;
      border: none;
      outline: none;
      background: #434a76;
      color: #ffffff;
      margin-bottom: 0.7rem;
      box-shadow: 0 3px 7px rgba(0, 0, 0, 0.28);
    }

    .btn-search {
      width: 100%;
      margin-bottom: 1.2rem;
    }

    /* ====== CHART SECTION ====== */
    .chart-section {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.2rem;
      margin-bottom: 1.4rem;
    }

    .chart-section canvas {
      background: #2a3056;
      border-radius: 12px;
      padding: 0.7rem;
    }

    /* ====== BACK BUTTON IN REVIEW ====== */
    .btn-back {
      width: 100%;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 768px) {
      .form-card {
        padding: 1.6rem 1.4rem;
      }

      .card-title {
        font-size: 1.3rem;
      }

      .page-content {
        margin-top: 85px;
      }

      .review-content {
        padding: 1.4rem 1.4rem 1.4rem 1.4rem;
      }
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

      .form-row {
        flex-direction: column;
      }

      .review-stats {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

  <header class="topbar">
    <div class="topbar-title">Driving Experience</div>
    <nav class="topbar-nav">
      <a href="dashboard.php" class="topbar-link">Dashboard</a>
      <a href="index.php" class="topbar-link">Home</a>
    </nav>
  </header>

  <!-- ⭐ MESSAGE BAR -->
  <div id="messageBar"></div>

  <!-- ⭐ MAIN WRAPPER -->
  <div class="page-content">

    <!-- ⭐ FORM CARD -->
    <div class="form-card" id="formContainer">
      <h2 class="card-title">Add New Driving Experience</h2>

      <form id="driveForm" onsubmit="return false;">
        <div class="form-group">
          <label>Date</label>
          <input type="date" id="date" name="date">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Start Time</label>
            <input type="time" id="starttime" name="startTime">
          </div>

          <div class="form-group">
            <label>End Time</label>
            <input type="time" id="endtime" name="endTime">
          </div>
        </div>

        <div class="form-group">
          <label>Kilometers</label>
          <input type="number" id="kms" name="kilometers" min="1">
        </div>

        <div class="form-group">
          <label>Weather</label>
          <select id="weatherDescription" name="weatherID">
            <option value="">Choose…</option>
          </select>
        </div>

        <div class="form-group">
          <label>Surface</label>
          <select id="surfaceDescription" name="surfaceID">
            <option value="">Choose…</option>
          </select>
        </div>

        <div class="form-group">
          <label>Traffic</label>
          <select id="trafficDescription" name="trafficID">
            <option value="">Choose…</option>
          </select>
        </div>

        <div class="form-group">
          <label>Accident</label>
          <select id="accidentDescription" name="accidentID">
            <option value="">Choose…</option>
          </select>
        </div>

        <div class="form-buttons">
          <button class="btn-primary" onclick="submitExperience()">Submit</button>
          <button class="btn-secondary" onclick="reviewDrivingExperience()">Review</button>
        </div>
      </form>
    </div>

    <!-- ⭐ REVIEW PANEL (Premium Version) -->
    <div id="Review" class="review-panel">
      <div class="review-content">

        <h2 class="review-title">Review Summary</h2>

        <div class="review-stats">
          <div class="stat-item"><span>Total Drives:</span> <strong id="totalDr"></strong></div>
          <div class="stat-item"><span>Total Hours:</span> <strong id="totalH"></strong></div>
          <div class="stat-item"><span>Total Distance:</span> <strong id="totalD"></strong></div>
          <div class="stat-item"><span>Total Accidents:</span> <strong id="totalAcc"></strong></div>
        </div>

        <!-- Search -->
        <h3 class="review-subtitle">Search by ID</h3>
        <input type="number" id="search_id" class="search-input" placeholder="Enter ID">
        <button class="btn-search" onclick="searchInfo()">Search</button>

        <!-- Charts -->
        <div class="chart-section">
          <canvas id="weatherChart"></canvas>
          <canvas id="surfaceChart"></canvas>
        </div>

        <button class="btn-back" onclick="hideReview()">Back</button>
      </div>
    </div>
  </div>

  <script>
    /* ============================================
   FETCH STATIC TABLES (Weather, Surface, Traffic, Accidents)
   ============================================ */

    async function loadComboBoxes() {
      try {
        const response = await fetch("https://shahin.alwaysdata.net/hwproject/get_static_data.php");
        const data = await response.json();

        if (data.status !== "success") {
          showMessage("Error loading dropdown data", "0, 80%, 50%");
          return;
        }

        fillSelect("weatherDescription", data.weather, "weatherID", "weatherDescription");
        fillSelect("surfaceDescription", data.surface, "surfaceID", "surfaceDescription");
        fillSelect("trafficDescription", data.traffic, "trafficID", "trafficDescription");
        fillSelect("accidentDescription", data.accidents, "accidentID", "accidentDescription");

      } catch (err) {
        showMessage("Connection error while loading dropdowns", "0, 80%, 50%");
      }
    }

    function fillSelect(selectId, items, valueKey, textKey) {
      const sel = document.getElementById(selectId);
      sel.innerHTML = `<option value="">Select option</option>`;

      items.forEach(row => {
        let opt = document.createElement("option");
        opt.value = row[valueKey];
        opt.textContent = row[textKey];
        sel.appendChild(opt);
      });
    }

    /* ============================================
       SHOW MESSAGE BAR
       ============================================ */

    function showMessage(txt, hslColor) {
      const msg = document.getElementById("messageBar");
      msg.textContent = txt;
      msg.style.background = `hsl(${hslColor})`;

      msg.classList.add("visible");

      setTimeout(() => {
        msg.classList.remove("visible");
      }, 3500);
    }

    /* ============================================
       SAVE DRIVING EXPERIENCE
       ============================================ */

    async function saveDrivingExperience() {

      const payload = {
        expID: document.getElementById("driver_exp_id").value,
        date: document.getElementById("date").value,
        startTime: document.getElementById("starttime").value,
        endTime: document.getElementById("endtime").value,
        kilometers: document.getElementById("kms").value,
        weatherID: document.getElementById("weatherDescription").value,
        surfaceID: document.getElementById("surfaceDescription").value,
        trafficID: document.getElementById("trafficDescription").value,
        accidentID: document.getElementById("accidentDescription").value,
      };

      // Simple validation
      for (const k in payload) {
        if (payload[k] === "") {
          showMessage("Please fill all fields", "0,80%,50%");
          return;
        }
      }

      if (payload.startTime >= payload.endTime) {
        showMessage("Start Time must be < End Time!", "0,80%,50%");
        return;
      }

      try {
        const response = await fetch("saveExperience.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.status === "success") {
          showMessage("Experience saved!", "120,80%,40%");
          document.querySelector(".form-card").reset;
        } else {
          showMessage(result.msg, "0,80%,50%");
        }

      } catch (err) {
        showMessage("Connection error while saving data", "0,80%,50%");
      }
    }

    /* ============================================
       REVIEW PANEL ANIMATION
       ============================================ */

    function reviewDrivingExperience() {
      document.querySelector(".review-panel").classList.add("active");
      loadReviewData();
    }

    function hideReview() {
      document.querySelector(".review-panel").classList.remove("active");
    }

    /* ============================================
       LOAD REVIEW STATISTICS
       ============================================ */

    async function loadReviewData() {
      try {
        const response = await fetch("get_experiences.php");
        const data = await response.json();

        if (data.status !== "success") {
          showMessage("Error loading statistics", "0,80%,50%");
          return;
        }

        const exp = data.records;

        // Fill stats
        document.getElementById("statTotal").textContent = exp.length;
        document.getElementById("statDistance").textContent =
          exp.reduce((a, b) => a + parseFloat(b.kilometers), 0);

        document.getElementById("statHours").textContent =
          exp.reduce((a, b) => a + getHours(b.startTime, b.endTime), 0).toFixed(2);

        document.getElementById("statAcc").textContent =
          exp.filter(x => x.accidentID !== "1").length;

        document.getElementById("statFine").textContent =
          exp.filter(x => x.accidentID === "2").length;

        document.getElementById("statTraff").textContent =
          exp.filter(x => x.accidentID === "3").length;

        document.getElementById("statRoad").textContent =
          exp.filter(x => x.accidentID === "4").length;

        document.getElementById("statOther").textContent =
          exp.filter(x => x.accidentID === "5").length;

        drawCharts(exp);

      } catch (err) {
        showMessage("Connection error while loading data", "0,80%,50%");
      }
    }

    /* ============================================
       HELPER → HOURS DIFFERENCE
       ============================================ */

    function getHours(start, end) {
      let s = new Date("2000-01-01 " + start);
      let e = new Date("2000-01-01 " + end);
      return (e - s) / 1000 / 60 / 60;
    }

    /* ============================================
       SEARCH EXPERIENCE BY ID
       ============================================ */

    async function searchExperience() {
      const id = document.getElementById("search_id").value;

      try {
        const res = await fetch("get_experiences.php");
        const data = await res.json();

        const match = data.records.find(e => e.expID == id);

        if (!match) {
          showMessage("Experience not found", "0,80%,50%");
          return;
        }

        showMessage(
          `Found! Date: ${match.date}, KM: ${match.kilometers}`,
          "120,80%,40%"
        );

      } catch (err) {
        showMessage("Connection error", "0,80%,50%");
      }
    }

    /* ============================================
       CHARTS (Weather + Surface)
       ============================================ */

    let weatherChart, surfaceChart;

    function drawCharts(exps) {

      // Count values
      const weatherCounts = {};
      const surfaceCounts = {};

      exps.forEach(e => {
        weatherCounts[e.weatherID] = (weatherCounts[e.weatherID] || 0) + 1;
        surfaceCounts[e.surfaceID] = (surfaceCounts[e.surfaceID] || 0) + 1;
      });

      // Weather
      if (weatherChart) weatherChart.destroy();
      weatherChart = new Chart(document.getElementById("weatherChart"), {
        type: "pie",
        data: {
          labels: Object.keys(weatherCounts),
          datasets: [{
            data: Object.values(weatherCounts),
            backgroundColor: ["#fdffb6", "#bdb2ff", "#87CEEB", "#9bf6ff", "#a0c4ff", "#caffbf"]
          }]
        }
      });

      // Surface
      if (surfaceChart) surfaceChart.destroy();
      surfaceChart = new Chart(document.getElementById("surfaceChart"), {
        type: "pie",
        data: {
          labels: Object.keys(surfaceCounts),
          datasets: [{
            data: Object.values(surfaceCounts),
            backgroundColor: ["#ffddd2", "#457b9d", "#f1faee", "#a8dadc"]
          }]
        }
      });
    }

    /* ============================================
       PAGE INIT
       ============================================ */

    window.onload = () => {
      loadComboBoxes();
    };
  </script>

</body>

</html>