<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAtividade;

class LogAtividadeController extends Controller
{
    public function index(Request $request)
    {
        $barbeariaId = Auth::user()->barbearia_id;

        $query = LogAtividade::where('barbearia_id', $barbeariaId)
            ->with('user')
            ->orderByDesc('created_at');

        if ($request->filled('busca')) {
            $busca = '%' . $request->busca . '%';
            $query->where(function ($q) use ($busca) {
                $q->where('descricao', 'ilike', $busca)
                  ->orWhere('acao', 'ilike', $busca);
            });
        }

        if ($request->filled('acao')) {
            $query->where('acao', $request->acao);
        }

        if ($request->filled('data')) {
            $query->whereDate('created_at', $request->data);
        }

        $logs  = $query->paginate(50)->withQueryString();
        $acoes = LogAtividade::where('barbearia_id', $barbeariaId)
            ->distinct()
            ->pluck('acao')
            ->sort()
            ->values();

        return view('panel.logs', compact('logs', 'acoes'));
    }
}
