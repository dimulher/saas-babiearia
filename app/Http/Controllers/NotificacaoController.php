<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Notificacao;

class NotificacaoController extends Controller
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $notificacoes = Notificacao::where('barbearia_id', $barbeariaId)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->tipo,
                    'icon' => $n->icone,
                    'color' => $n->cor,
                    'title' => $n->titulo,
                    'body' => $n->mensagem,
                    'time' => $n->created_at->diffForHumans(),
                    'read' => $n->lida
                ];
            });

        return response()->json($notificacoes);
    }

    public function markAsRead($id)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $notificacao = Notificacao::where('barbearia_id', $barbeariaId)->findOrFail($id);
        $notificacao->update(['lida' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        Notificacao::where('barbearia_id', $barbeariaId)
            ->where('lida', false)
            ->update(['lida' => true]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $notificacao = Notificacao::where('barbearia_id', $barbeariaId)->findOrFail($id);
        $notificacao->delete();

        return response()->json(['success' => true]);
    }
}
