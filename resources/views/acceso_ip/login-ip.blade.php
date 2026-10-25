<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protegido</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2c2f33;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .form-container {
            text-align: center;
            max-width: 500px;
            width: 100%;
            padding: 30px;
            background: #23272a;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }
        input[type="password"] {
            padding: 10px;
            width: calc(100% - 20px);
            margin-bottom: 20px;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #2c2f33;
            color: #fff;
        }
        button {
            padding: 10px 20px;
            color: #fff;
            background-color: #7289da;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #5b6eae;
        }
        .error {
            color: #e74c3c;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #e74c3c;
            border-radius: 5px;
            background-color: #2c2f33;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Introduce la contraseña</h1>
        @if (session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif
        <form action="{{ route('check-password') }}" method="POST">
            @csrf
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Acceder</button>
        </form>
    </div>
</body>
</html>
