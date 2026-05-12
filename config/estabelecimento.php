<?php

/*
|--------------------------------------------------------------------------
| Tipos de Estabelecimento
|--------------------------------------------------------------------------
| Cada tipo define os labels usados em todo o painel:
|  - label       : nome exibido do negócio
|  - profissional: como chamar os profissionais do estabelecimento
|  - icon        : ícone Font Awesome
*/

return [
    'tipos' => [
        'barbearia' => [
            'label'        => 'Barbearia',
            'profissional' => 'Barbeiro',
            'icon'         => 'fa-scissors',
        ],
        'salao_beleza' => [
            'label'        => 'Salão de Beleza',
            'profissional' => 'Cabeleireiro(a)',
            'icon'         => 'fa-spa',
        ],
        'estetica' => [
            'label'        => 'Clínica Estética',
            'profissional' => 'Esteticista',
            'icon'         => 'fa-star',
        ],
        'clinica' => [
            'label'        => 'Clínica',
            'profissional' => 'Profissional de Saúde',
            'icon'         => 'fa-heart-pulse',
        ],
        'tatuagem' => [
            'label'        => 'Estúdio de Tatuagem',
            'profissional' => 'Tatuador(a)',
            'icon'         => 'fa-pen-nib',
        ],
        'outros' => [
            'label'        => 'Outro',
            'profissional' => 'Profissional',
            'icon'         => 'fa-store',
        ],
    ],
];
