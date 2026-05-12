<?php

namespace App\Http\Controllers;

use App\Models\LogAtividade;
use Illuminate\Http\Request;

class LogController
{
    public function index(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $query = LogAtividade::where('barbearia_id', $barbeariaId)->with('user')->latest();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('descricao', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($sq) use ($request) {
                      $sq->where('nome', 'like', "%{$request->search}%");
                  });
            });
        }

        if ($request->acao && $request->acao !== 'todas') {
            $query->where('acao', $request->acao);
        }

        if ($request->data) {
            $query->whereDate('created_at', $request->data);
        }

        $logs = $query->paginate(50);
        
        return view('panel.logs', compact('logs'));
    }

    public function export(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $query = LogAtividade::where('barbearia_id', $barbeariaId)->with('user')->latest();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('descricao', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($sq) use ($request) {
                      $sq->where('nome', 'like', "%{$request->search}%");
                  });
            });
        }

        if ($request->acao && $request->acao !== 'todas') {
            $query->where('acao', $request->acao);
        }

        if ($request->data) {
            $query->whereDate('created_at', $request->data);
        }

        $logs = $query->get();

        $fileName = 'logs_atividade_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Data/Hora', 'Usuario', 'Acao', 'Descricao', 'IP'];

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            // Fix for Portuguese characters in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');

            foreach ($logs as $log) {
                $row['Data/Hora'] = $log->created_at->format('d/m/Y H:i:s');
                $row['Usuario']   = $log->user ? $log->user->name : 'Sistema';
                $row['Acao']      = $log->acao;
                $row['Descricao'] = $log->descricao;
                $row['IP']        = $log->ip;

                fputcsv($file, array_values($row), ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
