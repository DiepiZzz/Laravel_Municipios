<!DOCTYPE html>
<html>
<head>
    <title>Gráficas de Municipios</title>
    <style>
        /* Estilos básicos para el PDF */
        body {
            font-family: sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Gráficas de Datos de Municipios</h1>

    @if (isset($chartImage) && $chartImage)
        <img src="{{ $chartImage }}" alt="Gráfica de Municipios">
    @else
        <p style="text-align: center; color: red;">No se pudo cargar la imagen de la gráfica.</p>
    @endif

    <div class="footer">
        Generado el: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
