<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contenido Protegido</title>
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
        .content-container {
            background-color: #1F1F1F;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 100%;
        }
        h1, h2 {
            color: #BB86FC;
        }
        p {
            font-size: 1.1em;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #333;
        }
        tr:nth-child(even) {
            background-color: #2A2A2A;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #222;
            color: #E0E0E0;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #BB86FC;
            color: #121212;
            font-weight: bold;
        }
        button:hover {
            background-color: #9A4EED;
        }
        .logout-button {
            background-color: #CF6679;
        }
        .logout-button:hover {
            background-color: #B34D5E;
        }
        .error {
            color: #CF6679;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #CF6679;
            border-radius: 5px;
            background-color: #2A2A2A;
        }
    </style>
</head>
<body>
    <div class="content-container">
       
        <h2>Administrar Accesos IP</h2>
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <table>
            <thead>
                <tr>
                    <th>IP</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accessIps as $accessIp)
                    <tr>
                        <td>{{ $accessIp->numero_ip }}</td>
                        <td>{{ $accessIp->descripcion }}</td>
                        <td>{{ $accessIp->fch }}</td>
                        <td>
                            <form action="{{ route('access-ips.destroy', $accessIp->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('access-ips.store') }}" method="POST">
            @csrf
            <input type="text" name="numero_ip" placeholder="Numero IP">
            <input type="text" name="descripcion" placeholder="Descripción">
            <button type="submit">Agregar</button>
        </form>

        <form action="{{ route('logout-ip') }}" method="POST">
            @csrf
            <button type="submit" class="logout-button">Cerrar Sesión</button>
        </form>
    </div>
</body>
</html>
