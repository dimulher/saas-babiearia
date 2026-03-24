
<?php $__env->startSection('title', 'Contas'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Contas</h1>
        <button class="flex items-center gap-2 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800">
            <i class="fa-solid fa-plus text-xs"></i> Nova Conta
        </button>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 bg-gray-100 rounded-xl p-1 w-fit">
        <a href="/panel/contas" class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-gray-900 shadow-sm">Todas</a>
        <a href="/panel/contas/parceladas" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900">Parceladas</a>
        <a href="/panel/contas/recorrentes" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900">Recorrentes</a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3">
        <input type="text" placeholder="Pesquisar conta..." class="border border-gray-200 rounded-xl px-3 py-2 text-sm w-60 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todos os status</option>
            <option>A pagar</option>
            <option>Paga</option>
            <option>Vencida</option>
        </select>
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todas as categorias</option>
            <option>Aluguel</option>
            <option>Energia</option>
            <option>Produtos</option>
            <option>Outros</option>
        </select>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Descrição</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Categoria</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Vencimento</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Status</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Valor</th>
                    <th class="py-3 px-4 w-24"></th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6" class="py-12 text-center text-gray-400 text-sm">Nenhuma conta cadastrada.</td></tr>
            </tbody>
        </table>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dede\saas-agendamento-barbiearia2026\resources\views/panel/contas/index.blade.php ENDPATH**/ ?>