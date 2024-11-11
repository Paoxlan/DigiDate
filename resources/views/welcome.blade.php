<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiDate</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #000; /* Black background */
            color: #fff; /* White text */
            position: relative;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 3rem;
            color: #fff; /* White text */
        }
        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .button {
            padding: 10px 20px;
            font-size: 1rem;
            margin-left: 10px;
            text-decoration: none;
            color: #000; /* Black text on button */
            background-color: #fff; /* White button */
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #ccc; /* Lighter color on hover */
        }
    </style>
</head>
<body>
<div class="top-right">
    <a href="{{ route('login') }}" class="button">Login</a>
    <a href="{{ route('register') }}" class="button">Registreren</a>
</div>

<div class="container">
    <h1>DigiDate</h1>
</div>
</body>
</html>
