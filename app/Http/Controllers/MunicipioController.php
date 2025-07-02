<?php

namespace App\Http\Controllers;

use App\Interfaces\MunicipioServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Dompdf\Dompdf; 
use Dompdf\Options; 

class MunicipioController extends Controller
{
    private MunicipioServiceInterface $municipioService;

    public function __construct(MunicipioServiceInterface $municipioService)
    {
        $this->municipioService = $municipioService;
    }

    public function index(): View
    {
        $municipios = $this->municipioService->getAllMunicipios();
        return view('municipios.index', compact('municipios'));
    }

    public function create(): View
    {
        return view('municipios.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'pais' => 'required|string|max:255',
            'alcalde' => 'nullable|string|max:255',
            'gobernador' => 'nullable|string|max:255',
            'patronoReligioso' => 'nullable|string|max:255',
            'numHabitantes' => 'nullable|integer|min:0',
            'numCasas' => 'nullable|integer|min:0',
            'numParques' => 'nullable|integer|min:0',
            'numColegios' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $municipio = $this->municipioService->createNewMunicipio($validatedData);

        if ($municipio) {
            return redirect()->route('municipios.index')->with('status', '¡Municipio creado con éxito!');
        }

        return back()->withInput()->withErrors(['error' => 'No se pudo crear el municipio.']);
    }

    public function edit(int $id): View|RedirectResponse
    {
        $municipio = $this->municipioService->getMunicipioById($id);

        if (!$municipio) {
            return redirect()->route('municipios.index')->withErrors(['error' => 'Municipio no encontrado.']);
        }

        return view('municipios.edit', compact('municipio'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'departamento' => 'sometimes|nullable|string|max:255',
            'pais' => 'sometimes|required|string|max:255',
            'alcalde' => 'sometimes|nullable|string|max:255',
            'gobernador' => 'sometimes|nullable|string|max:255',
            'patronoReligioso' => 'sometimes|nullable|string|max:255',
            'numHabitantes' => 'sometimes|nullable|integer|min:0',
            'numCasas' => 'sometimes|nullable|integer|min:0',
            'numParques' => 'sometimes|nullable|integer|min:0',
            'numColegios' => 'sometimes|nullable|integer|min:0',
            'descripcion' => 'sometimes|nullable|string',
        ]);

        $municipio = $this->municipioService->updateMunicipioDetails($id, $validatedData);

        if ($municipio) {
            return redirect()->route('municipios.index')->with('status', '¡Municipio actualizado con éxito!');
        }

        return back()->withInput()->withErrors(['error' => 'No se pudo actualizar el municipio.']);
    }

    public function destroy(int $id): RedirectResponse
    {
        $deleted = $this->municipioService->deleteExistingMunicipio($id);

        if ($deleted) {
            return redirect()->route('municipios.index')->with('status', '¡Municipio eliminado con éxito!');
        }

        return back()->withErrors(['error' => 'No se pudo eliminar el municipio o no fue encontrado.']);
    }

    /**
     * Muestra la vista de gráficas de municipios.
     *
     * @return View
     */
    public function showGraficas(): View
    {
        
        $municipios = $this->municipioService->getAllMunicipios();

        
        $chartData = $municipios->map(function ($municipio) {
            return [
                'nombre' => $municipio->nombre,
                'numHabitantes' => (int) $municipio->numHabitantes,
                'numCasas' => (int) $municipio->numCasas,
                'numParques' => (int) $municipio->numParques,
                'numColegios' => (int) $municipio->numColegios,
            ];
        })->toArray();

        
        return view('municipios.graficas', compact('chartData'));
    }

    /**
     * Genera y descarga un PDF con las gráficas de municipios.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadGraficasPdf(Request $request)
    {
        
        $request->validate([
            'chartImage' => 'required|string',
        ]);

        $chartImage = $request->input('chartImage');

        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); 

        $dompdf = new Dompdf($options);

        
        
        $html = view('pdfs.graficas', compact('chartImage'))->render();

        $dompdf->loadHtml($html);

        
        $dompdf->setPaper('A4', 'landscape'); 

        
        $dompdf->render();

        
        return response()->stream(function() use ($dompdf) {
            echo $dompdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="graficas_municipios.pdf"',
        ]);
    }
}
