<?php 
require_once __DIR__ . "/includes/includeDB.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Driving Experience – Home</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1d1f33, #4e5d9d);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #appName {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 3rem;
            color: #ffb347;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .container {
            text-align: center;
            background: #333a6e;
            padding: 3rem 4rem;
            border-radius: 12px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.35);
            width: 450px;
        }

        h1 {
            margin-bottom: 1.5rem;
            color: #ffb347;
            font-size: 2rem;
        }

        .btn {
            width: 80%;
            padding: 1rem;
            margin: 1rem 0;
            border: 2px solid #ffb347;
            background-color: #333a6e;
            color: #ffb347;
            font-size: 1.2rem;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn:hover {
            background-color: #ffb347;
            color: #2d3356;
        }

        .logout {
            margin-top: 2rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        a {
            color: #ffb347;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 id="appName">DriveX</h1> <button class="btn" onclick="location.href='dashboard.php'"> Dashboard </button> <button class="btn" onclick="location.href='WebForm.php'"> Driving Experience Form </button>
    </div>
</body>

</html>