<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficas de Municipios</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Estilo personalizado para la fuente Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl w-full">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Gráficas de Datos por Municipio</h1>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('municipios.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105 text-center">
                    Volver al Listado
                </a>
                <button id="downloadPdfBtn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105 text-center">
                    Descargar PDF
                </button>
            </div>
        </div>

        <div class="relative h-96 w-full">
            <canvas id="municipiosChart"></canvas>
        </div>
    </div>

    <script>
        // Datos de los municipios pasados desde Laravel
        let chartData = [];
        try {
            chartData = JSON.parse('{!! json_encode($chartData ?? []) !!}');
            console.log('Parsed chartData:', chartData);
        } catch (e) {
            console.error('Error al parsear chartData desde Blade:', e);
        }

        // Extraer etiquetas (nombres de municipios)
        const labels = chartData.map(municipio => municipio.nombre);

        // Extraer datos para cada métrica
        const habitantesData = chartData.map(municipio => municipio.numHabitantes);
        const casasData = chartData.map(municipio => municipio.numCasas);
        const parquesData = chartData.map(municipio => municipio.numParques);
        const colegiosData = chartData.map(municipio => municipio.numColegios);

        // Configuración de la gráfica de barras
        const ctx = document.getElementById('municipiosChart').getContext('2d');
        const municipiosChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfica: barras
            data: {
                labels: labels, // Nombres de los municipios
                datasets: [
                    {
                        label: 'N° Habitantes',
                        data: habitantesData,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)', // blue-500
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'N° Casas',
                        data: casasData,
                        backgroundColor: 'rgba(16, 185, 129, 0.6)', // emerald-500
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'N° Parques',
                        data: parquesData,
                        backgroundColor: 'rgba(245, 158, 11, 0.6)', // amber-500
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'N° Colegios',
                        data: colegiosData,
                        backgroundColor: 'rgba(139, 92, 246, 0.6)', // violet-500
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('es-CO').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('es-CO').format(value);
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Municipio'
                        }
                    }
                }
            }
        });

        // Lógica para el botón "Descargar PDF"
        document.getElementById('downloadPdfBtn').addEventListener('click', function() {
            // Obtener la imagen Base64 de la gráfica
            const chartImage = municipiosChart.toBase64Image('image/png', 1); // 1 = calidad (100%)

            // Obtener el token CSRF de Laravel
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Enviar la imagen al backend para generar el PDF
            fetch("{{ route('municipios.downloadPdf') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // Incluir el token CSRF
                },
                body: JSON.stringify({
                    chartImage: chartImage,
                    // Puedes enviar otros datos si los necesitas en el PDF, ej.
                    // municipiosData: chartData
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al generar el PDF: ' + response.statusText);
                }
                return response.blob(); // Obtener la respuesta como un Blob (archivo binario)
            })
            .then(blob => {
                // Crear un enlace temporal para descargar el PDF
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'graficas_municipios.pdf'; // Nombre del archivo PDF
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url); // Limpiar la URL temporal
            })
            .catch(error => {
                console.error('Error al descargar el PDF:', error);
                alert('No se pudo descargar el PDF. Por favor, inténtalo de nuevo.');
            });
        });
    </script>
</body>
</html>
