<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Driving Experience</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
      margin: 90px auto 40px auto; /* space for topbar */
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

    /* ====== MULTISELECT FOR MANEUVERS ====== */
    #maneuvers {
      min-height: 120px;
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

    /* Dropdown wrapper */
.dropdown-wrapper {
  position: relative;
}

/* Closed display area */
.dropdown-display {
  background: #434a76;
  padding: 0.7rem 0.9rem;
  border-radius: 8px;
  cursor: pointer;
  color: #fff;
  box-shadow: 0 3px 7px rgba(0,0,0,0.28);
}

/* Hidden list */
.dropdown-list {
  display: none;
  margin-top: 6px;
  background: #333a6e;
  border-radius: 8px;
  padding: 0.5rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  z-index: 50;
}

.dropdown-list select {
  width: 100%;
  height: 150px;
  padding: 0.5rem;
  background: #434a76;
  border-radius: 8px;
  color: #fff;
}


    .btn-primary:hover::before,
    .btn-secondary:hover::before,
    .btn-search:hover::before,
    .btn-back:hover::before {
      left: 0;
      right: 0;
      opacity: 1;
    }

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
      <a href="webform.php" class="topbar-link">Form</a>
    </nav>
  </header>

  <!-- MESSAGE BAR -->
  <div id="messageBar"></div>

  <!-- MAIN WRAPPER -->
  <div class="page-content">

    <!-- FORM CARD -->
    <div class="form-card" id="formContainer">
      <h2 class="card-title">Add New Driving Experience</h2>

      <form id="driveForm" onsubmit="return false;">
        <div class="form-group">
          <label for="expID">Driving Experience ID</label>
          <input type="number" id="expID" name="expID" min="1">
        </div>

        <div class="form-group">
          <label for="date">Date</label>
          <input type="date" id="date" name="date">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="starttime">Start Time</label>
            <input type="time" id="starttime" name="startTime">
          </div>

          <div class="form-group">
            <label for="endtime">End Time</label>
            <input type="time" id="endtime" name="endTime">
          </div>
        </div>

        <div class="form-group">
          <label for="kms">Kilometers</label>
          <input type="number" id="kms" name="kilometers" min="1">
        </div>

        <div class="form-group">
          <label for="weatherDescription">Weather</label>
          <select id="weatherDescription" name="weatherID">
            <option value="">Choose…</option>
          </select>
        </div>

        <div class="form-group">
          <label for="surfaceDescription">Surface</label>
          <select id="surfaceDescription" name="surfaceID">
            <option value="">Choose…</option>
          </select>
        </div>

        <div class="form-group">
          <label for="trafficDescription">Traffic</label>
          <select id="trafficDescription" name="trafficID">
            <option value="">Choose…</option>
          </select>
        </div>

        <div class="form-group">
  <label for="maneuvers">Maneuvers</label>

  <div id="maneuverDropdown" class="dropdown-wrapper">
    <div id="maneuverDisplay" class="dropdown-display">
      Choose maneuvers…
    </div>

    <div id="maneuverList" class="dropdown-list">
      <select id="maneuvers" name="maneuvers" multiple></select>
    </div>
  </div>
</div>

        <div class="form-buttons">
          <button type="button" class="btn-primary" onclick="submitExperience()">Submit</button>
          <button type="button" class="btn-secondary" onclick="reviewDrivingExperience()">Review</button>
        </div>
      </form>
    </div>

    <!-- REVIEW PANEL -->
    <div id="Review" class="review-panel">
      <div class="review-content">

        <h2 class="review-title">Review Summary</h2>

        <div class="review-stats">
          <div class="stat-item"><span>Total Drives:</span> <strong id="totalDr">0</strong></div>
          <div class="stat-item"><span>Total Hours:</span> <strong id="totalH">0</strong></div>
          <div class="stat-item"><span>Total Distance (km):</span> <strong id="totalD">0</strong></div>
          <div class="stat-item"><span>Total Maneuvers Logged:</span> <strong id="totalMan">0</strong></div>
        </div>

        <h3 class="review-subtitle">Search by Experience ID</h3>
        <input type="number" id="search_id" class="search-input" placeholder="Enter ID">
        <button type="button" class="btn-search" onclick="searchInfo()">Search</button>
        <div id="searchResultBox" 
     style="
       background:#2a3056;
       padding:1rem 1.3rem;
       border-radius:12px;
       margin-top:1rem;
       margin-bottom:1rem;
       color:#ffffff;
       display:none;
       line-height:1.5;
       box-shadow:0 4px 10px rgba(0,0,0,0.25);
     ">
</div>
        <button type="button" class="btn-back" onclick="hideReview()">Back</button>
      </div>
    </div>
  </div>

  <script>
    // ====== GLOBAL STATE ======
    let nextExpID = 1;
    let weatherMap = {};
    let surfaceMap = {};
    let trafficMap = {};
    let maneuverMap = {};

    let weatherChart = null;
    let surfaceChart = null;

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

    // Toggle list open/close
document.getElementById("maneuverDisplay").onclick = function () {
  const list = document.getElementById("maneuverList");
  list.style.display = (list.style.display === "block") ? "none" : "block";
};

// Close when clicking outside
document.addEventListener("click", function (e) {
  const box = document.getElementById("maneuverDropdown");
  if (!box.contains(e.target)) {
    document.getElementById("maneuverList").style.display = "none";
  }
});

// Update label with selected items
document.getElementById("maneuvers").addEventListener("change", function () {
  const selected = Array.from(this.selectedOptions).map(o => o.textContent);
  document.getElementById("maneuverDisplay").textContent =
    selected.length ? selected.join(", ") : "Choose maneuvers…";
});


    // ====== LOAD STATIC DATA (WEATHER, SURFACE, TRAFFIC, MANEUVERS) ======
    async function loadComboBoxes() {
      try {
        const response = await fetch("https://shahin.alwaysdata.net/webproject/get_static_data.php");
        const data = await response.json();

        if (data.status !== "success") {
          showMessage("Error loading dropdown data", "0, 80%, 50%");
          return;
        }

        // Build maps
        weatherMap = {};
        surfaceMap = {};
        trafficMap = {};
        maneuverMap = {};

        data.weather.forEach(w => {
          weatherMap[w.weatherID] = w.weatherDescription;
        });
        data.surface.forEach(s => {
          surfaceMap[s.surfaceID] = s.surfaceDescription;
        });
        data.traffic.forEach(t => {
          trafficMap[t.trafficID] = t.trafficDescription;
        });
        data.maneuvers.forEach(m => {
          maneuverMap[m.maneuverID] = m.maneuverDescription;
        });

        fillSelect("weatherDescription", data.weather, "weatherID", "weatherDescription", false);
        fillSelect("surfaceDescription", data.surface, "surfaceID", "surfaceDescription", false);
        fillSelect("trafficDescription", data.traffic, "trafficID", "trafficDescription", false);
        fillSelect("maneuvers", data.maneuvers, "maneuverID", "maneuverDescription", true);

      } catch (err) {
        showMessage("Connection error while loading dropdowns", "0, 80%, 50%");
      }
    }

    function fillSelect(selectId, items, valueKey, textKey, isMultiple) {
      const sel = document.getElementById(selectId);
      if (!sel) return;

      if (isMultiple) {
        sel.innerHTML = "";
      } else {
        sel.innerHTML = `<option value="">Choose…</option>`;
      }

      items.forEach(row => {
        const opt = document.createElement("option");
        opt.value = row[valueKey];
        opt.textContent = row[textKey];
        sel.appendChild(opt);
      });
    }

    // ====== GET NEXT EXPERIENCE ID ======
    async function initNextExpID() {
      const expInput = document.getElementById("expID");
      try {
        const res = await fetch("https://shahin.alwaysdata.net/webproject/get_experiences.php");
        const data = await res.json();

        const exps = Array.isArray(data) ? data : (data.records || []);
        let maxId = 0;
        exps.forEach(e => {
          const id = parseInt(e.expID, 10);
          if (id > maxId) maxId = id;
        });
        nextExpID = maxId + 1;
      } catch (err) {
        nextExpID = 1;
      }

      expInput.value = nextExpID;
      expInput.placeholder = "Next ID: " + nextExpID;
    }

    // ====== SUBMIT EXPERIENCE ======
    async function submitExperience() {
      const expInput = document.getElementById("expID");
      const date = document.getElementById("date").value;
      const start = document.getElementById("starttime").value;
      const end = document.getElementById("endtime").value;
      const kms = document.getElementById("kms").value;
      const weatherID = document.getElementById("weatherDescription").value;
      const surfaceID = document.getElementById("surfaceDescription").value;
      const trafficID = document.getElementById("trafficDescription").value;
      const maneuversSelect = document.getElementById("maneuvers");

      let expID = parseInt(expInput.value, 10);
      if (!expID || expID <= 0) {
        expID = nextExpID || 1;
      }

      // Collect maneuvers array
      const maneuvers = Array.from(maneuversSelect.selectedOptions).map(opt => parseInt(opt.value, 10));

      // Basic validation
      if (!date || !start || !end || !kms || !weatherID || !surfaceID || !trafficID || maneuvers.length === 0) {
        showMessage("Please fill all fields and choose at least one maneuver", "0, 80%, 50%");
        return;
      }

      if (start >= end) {
        showMessage("Start time must be earlier than end time", "0, 80%, 50%");
        return;
      }

      const payload = {
        expID: expID,
        date: date,
        startTime: start,
        endTime: end,
        kilometers: parseFloat(kms),
        weatherID: parseInt(weatherID, 10),
        surfaceID: parseInt(surfaceID, 10),
        trafficID: parseInt(trafficID, 10),
        maneuvers: maneuvers
      };

      try {
        const response = await fetch("https://shahin.alwaysdata.net/webproject/save_experience.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.status === "success") {
          showMessage("Experience saved successfully", "120, 80%, 35%");
          setTimeout(() => {
        window.location.reload();
    }, 1000);


          // Reset form
          document.getElementById("driveForm").reset();

          // Update nextExpID
          nextExpID = expID + 1;
          expInput.value = nextExpID;
          expInput.placeholder = "Next ID: " + nextExpID;

        } else {
          showMessage(result.msg || "Error while saving experience", "0, 80%, 50%");
        }
      } catch (err) {
        showMessage("Connection error while saving data", "0, 80%, 50%");
      }
    }

    // ====== REVIEW PANEL ======
    function reviewDrivingExperience() {
      const panel = document.getElementById("Review");
      panel.classList.add("active");
      loadReviewData();
    }

    function hideReview() {
      const panel = document.getElementById("Review");
      panel.classList.remove("active");
    }

    // ====== LOAD REVIEW STATISTICS ======
    async function loadReviewData() {
      try {
        const response = await fetch("https://shahin.alwaysdata.net/webproject/get_experiences.php");
        const data = await response.json();

        const exps = Array.isArray(data) ? data : (data.records || []);
        if (!Array.isArray(exps)) {
          showMessage("Error loading statistics", "0, 80%, 50%");
          return;
        }

        const totalDr = exps.length;
        let totalDistance = 0;
        let totalHours = 0;
        let totalMan = 0;

        const weatherCounts = {};
        const surfaceCounts = {};

        exps.forEach(e => {
          const km = parseFloat(e.kilometers) || 0;
          totalDistance += km;

          const hours = getHours(e.startTime, e.endTime);
          totalHours += hours;

          const maneuvers = Array.isArray(e.maneuvers) ? e.maneuvers : [];
          totalMan += maneuvers.length;

          const wID = e.weatherID;
          const sID = e.surfaceID;

          weatherCounts[wID] = (weatherCounts[wID] || 0) + 1;
          surfaceCounts[sID] = (surfaceCounts[sID] || 0) + 1;
        });

        document.getElementById("totalDr").textContent = totalDr;
        document.getElementById("totalD").textContent = totalDistance.toFixed(1);
        document.getElementById("totalH").textContent = totalHours.toFixed(2);
        document.getElementById("totalMan").textContent = totalMan;


      } catch (err) {
        showMessage("Connection error while loading data", "0, 80%, 50%");
      }
    }

    // ====== HOURS DIFFERENCE ======
    function getHours(start, end) {
      if (!start || !end) return 0;
      const s = new Date("2000-01-01T" + start);
      const e = new Date("2000-01-01T" + end);
      const diffMs = e - s;
      return diffMs / 1000 / 60 / 60;
    }


    
   async function searchInfo() {
  const idVal = document.getElementById("search_id").value;
  const id = parseInt(idVal, 10);
  const box = document.getElementById("searchResultBox");

  box.style.display = "none";   // reset

  if (!id) {
    box.innerHTML = `<strong style="color:#ff6b6b">Please enter a valid ID.</strong>`;
    box.style.display = "block";
    return;
  }

  try {
    const res = await fetch("https://shahin.alwaysdata.net/webproject/get_experiences.php");
    const data = await res.json();
    const exps = Array.isArray(data) ? data : (data.records || []);

    const match = exps.find(e => parseInt(e.expID, 10) === id);

    if (!match) {
      box.innerHTML = `<strong style="color:#ff6b6b">No experience found for ID ${id}.</strong>`;
      box.style.display = "block";
      return;
    }

    // MAP VALUES
    const weatherName  = weatherMap[match.weatherID]  || ("Weather " + match.weatherID);
    const surfaceName  = surfaceMap[match.surfaceID]  || ("Surface " + match.surfaceID);
    const trafficName  = trafficMap[match.trafficID]  || ("Traffic " + match.trafficID);

    const manList = Array.isArray(match.maneuvers)
  ? match.maneuvers.map(id => maneuverMap[id] || ("M" + id))
  : [];

    // BUILD HTML RESULT
    box.innerHTML = `
      <h3 style="color:#ffb347; margin-bottom:0.4rem;">Experience Details</h3>

      <p><strong>Date:</strong> ${match.date}</p>
      <p><strong>Time:</strong> ${match.startTime} – ${match.endTime}</p>
      <p><strong>Kilometers:</strong> ${match.kilometers}</p>

      <p><strong>Weather:</strong> ${weatherName}</p>
      <p><strong>Surface:</strong> ${surfaceName}</p>
      <p><strong>Traffic:</strong> ${trafficName}</p>

      <p><strong>Maneuvers:</strong> 
         ${manList.length ? manList.join(", ") : "None"}
      </p>
    `;

    box.style.display = "block";

  } catch (err) {
    box.innerHTML = `<strong style="color:#ff6b6b">Connection error while searching.</strong>`;
    box.style.display = "block";
  }
}



    // ====== INIT PAGE ======
    window.onload = () => {
      loadComboBoxes();
      initNextExpID();
    };
  </script>
</body>
</html>
