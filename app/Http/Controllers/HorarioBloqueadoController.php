<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HorarioBloqueado;
use App\Models\Profissional;

class HorarioBloqueadoController extends Controller
{
    public function index(Request $request)
    {
        $barbeariaId   = Auth::user()->barbearia_id;
        $profissionais = Profissional::where('barbearia_id', $barbeariaId)->orderBy('nome')->get();

        $query = HorarioBloqueado::where('barbearia_id', $barbeariaId)
            ->with('profissional')
            ->orderByDesc('data_inicio');

        if ($request->filled('profissional_id')) {
            $query->where('profissional_id', $request->profissional_id);
        }

        if ($request->filled('data')) {
            $query->where('data_inicio', '<=', $request->data)
                  ->where('data_fim', '>=', $request->data);
        }

        $bloqueios = $query->get();

        return view('panel.bloquear-horarios', compact('bloqueios', 'profissionais'));
    }

    public function store(Request $request)
    {
        $barbeariaId = Auth::user()->barbearia_id;

        $request->validate([
            'data_inicio'      => 'required|date',
            'data_fim'         => 'required|date|after_or_equal:data_inicio',
            'profissional_id'  => 'nullable|exists:profissionais,id',
            'hora_inicio'      => 'nullable|date_format:H:i',
            'hora_fim'         => 'nullable|date_format:H:i|after:hora_inicio',
            'motivo'           => 'nullable|string|max:255',
        ]);

        $diaTodo = $request->boolean('dia_todo');

        HorarioBloqueado::create([
            'barbearia_id'    => $barbeariaId,
            'profissional_id' => $request->profissional_id ?: null,
            'data_inicio'     => $request->data_inicio,
            'data_fim'        => $request->data_fim,
            'hora_inicio'     => $diaTodo ? null : $request->hora_inicio,
            'hora_fim'        => $diaTodo ? null : $request->hora_fim,
            'motivo'          => $request->motivo,
            'dia_todo'        => $diaTodo,
        ]);

        return back()->with('success', 'Horário bloqueado com sucesso.');
    }

    public function destroy($id)
    {
        $barbeariaId = Auth::user()->barbearia_id;

        HorarioBloqueado::where('id', $id)
            ->where('barbearia_id', $barbeariaId)
            ->delete();

        return back()->with('success', 'Bloqueio removido.');
    }
}
