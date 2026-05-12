<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\Profissional;
use App\Models\Barbearia;
use App\Services\LogAtividadeService;
use Illuminate\Http\Request;

class ExpedienteController
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $profissionais = Profissional::where('barbearia_id', $barbeariaId)->with('expedientes')->get();
        return view('panel.expedientes', compact('profissionais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'profissional_id' => 'required|exists:profissionais,id',
            'expedientes' => 'required|array|size:7',
            'expedientes.*.dia_semana' => 'required|integer|min:0|max:6',
            'expedientes.*.hora_inicio' => 'required_if:expedientes.*.ativo,true',
            'expedientes.*.hora_fim' => 'required_if:expedientes.*.ativo,true',
        ]);

        $barbeariaId = auth()->user()->barbearia_id;
        $profissional = Profissional::where('barbearia_id', $barbeariaId)->findOrFail($request->profissional_id);

        foreach ($request->expedientes as $item) {
            Expediente::updateOrCreate(
                [
                    'barbearia_id' => $barbeariaId,
                    'profissional_id' => $request->profissional_id,
                    'dia_semana' => $item['dia_semana'],
                ],
                [
                    'hora_inicio' => $item['hora_inicio'] ?? '08:00',
                    'hora_fim' => $item['hora_fim'] ?? '18:00',
                    'intervalo_inicio' => $item['intervalo_inicio'] ?? null,
                    'intervalo_fim' => $item['intervalo_fim'] ?? null,
                    'ativo' => isset($item['ativo']) && $item['ativo'],
                ]
            );
        }

        LogAtividadeService::log('expediente_atualizado', "Expediente do profissional '{$profissional->nome}' atualizado.", 'Profissional', $profissional->id);

        return redirect()->route('panel.expedientes')->with('success', 'Expediente atualizado com sucesso!');
    }
}
