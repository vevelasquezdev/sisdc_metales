<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .error-container {
            text-align: center;
            max-width: 500px;
            width: 100%;
            padding: 20px;
            background: #34495e;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 0.5em;
            color: #ff1900;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 1em;
        }
        .back-link {
            margin-top: 2em;
            display: inline-block;
            padding: 10px 20px;
            color: #ecf0f1;
            background-color: #2980b9;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover {
            background-color: #1abc9c;
        }
        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 1em;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Acceso Denegado</h1>
        <p>Lo sentimos, no tienes permiso para acceder a esta página.</p>
        <img src="images/403.png" alt="Error 403" width="80%">
        
    </div>
</body>
</html>
