<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app.name', 'BarberSAAS')); ?> - <?php echo $__env->yieldContent('title', 'Painel'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .sidebar-item { transition: background 0.15s; }
        .sidebar-item:hover { background-color: #f3f4f6; border-radius: 8px; }
        .sidebar-item.active { background-color: #f3f4f6; border-radius: 8px; font-weight: 600; color: #111827; }
        .sidebar-submenu { border-left: 2px solid #e5e7eb; margin-left: 1.25rem; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ mobileOpen: false }">

    
    <header class="sticky top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 lg:ps-[208px]">
        <nav class="px-4 sm:px-6 flex basis-full items-center w-full mx-auto">

            
            <div class="me-5 lg:me-0 lg:hidden">
                <a href="/panel/dashboard" class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-none">
                    <span class="text-gray-900 font-black text-lg tracking-tight">BARBER</span><span class="text-indigo-600 font-black text-lg">SAAS</span>
                </a>
            </div>

            <div class="w-full flex items-stretch justify-end md:justify-between">
                
                <div class="hidden lg:flex justify-start items-stretch">
                    <a href="/panel/dashboard" class="inline-flex px-4 justify-center items-center text-sm font-semibold border border-transparent <?php echo e(request()->is('panel/dashboard') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-800 hover:bg-gray-100'); ?>">Dashboard</a>
                    <a href="/panel/agendamentos" class="inline-flex px-4 justify-center items-center text-sm font-semibold border border-transparent <?php echo e(request()->is('panel/agendamentos') ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-800 hover:bg-gray-100'); ?>">Agendamentos</a>
                </div>

                
                <div class="flex flex-row items-center justify-end gap-1">
                    <a href="#" class="hidden lg:flex inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-lg font-semibold text-xs text-white hover:bg-gray-800 transition">
                        <i class="fa-brands fa-google-play mr-1.5"></i> Baixar App na Google Play
                    </a>

                    
                    <div class="relative inline-flex" x-data="{ avatarOpen: false }">
                        <button @click="avatarOpen = !avatarOpen" class="size-[38px] inline-flex justify-center items-center text-sm font-semibold rounded-full border border-transparent focus:outline-none">
                            <div class="w-9 h-9 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold select-none">BG</div>
                        </button>
                        <div x-show="avatarOpen" x-cloak @click.outside="avatarOpen = false"
                             class="absolute right-0 top-full mt-2 z-50 min-w-56 bg-white shadow-md rounded-lg border border-gray-100">
                            <div class="py-3 px-5 bg-gray-50 rounded-t-lg">
                                <p class="text-xs text-gray-500">Logado com</p>
                                <p class="text-sm font-medium text-gray-800">gabriel@barbearia.com</p>
                            </div>
                            <div class="p-1.5 space-y-0.5">
                                <a href="/panel/configuracoes/conta" class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100">
                                    <i class="fa-solid fa-user-gear w-4 text-gray-400"></i> Minha Conta
                                </a>
                                <a href="/logout" class="flex items-center gap-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100">
                                    <i class="fa-solid fa-right-from-bracket w-4 text-gray-400"></i> Sair
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    
    <div class="sticky top-[49px] inset-x-0 z-20 bg-white border-y px-4 sm:px-6 lg:hidden">
        <div class="flex items-center py-2">
            
            <button @click="mobileOpen = true"
                class="px-4 py-1 flex justify-center items-center gap-x-2 border border-gray-200 text-gray-800 hover:text-gray-500 rounded-lg focus:outline-none text-sm font-medium">
                <i class="fa-solid fa-bars text-xs"></i>
                Menu
            </button>

            
            <ol class="ms-3 flex items-center whitespace-nowrap">
                <li class="flex items-center text-sm text-gray-500">
                    App
                    <svg class="shrink-0 mx-2 overflow-visible size-2.5 text-gray-400" fill="none" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke-linecap="round" stroke-width="2" stroke="currentColor"/>
                    </svg>
                </li>
                <li class="text-sm font-semibold text-gray-800 truncate"><?php echo $__env->yieldContent('title', 'Painel'); ?></li>
            </ol>
        </div>
    </div>

    
    <div class="hidden lg:block fixed inset-y-0 start-0 z-[60] w-[208px] bg-white border-e border-gray-200">
        <div class="flex flex-col h-full">
            
            <div class="px-5 pt-5 pb-4 border-b border-gray-100">
                <a href="/panel/dashboard" class="flex items-center gap-1">
                    <span class="text-gray-900 font-black text-lg tracking-tight">BARBER</span><span class="text-indigo-600 font-black text-lg">SAAS</span>
                </a>
            </div>
            
            <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5 text-sm text-gray-600" x-data="{}">
                <?php echo $__env->make('layouts.partials.nav-items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </nav>
        </div>
    </div>

    
    
    <div x-show="mobileOpen" x-cloak
         @click="mobileOpen = false"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[59] bg-black/50 lg:hidden">
    </div>

    
    <div x-show="mobileOpen" x-cloak
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 start-0 z-[60] w-[260px] bg-white border-e border-gray-200 flex flex-col lg:hidden shadow-xl">

        
        <div class="px-5 pt-5 pb-4 border-b border-gray-100 flex items-center justify-between">
            <a href="/panel/dashboard" @click="mobileOpen = false" class="flex items-center gap-1">
                <span class="text-gray-900 font-black text-lg tracking-tight">BARBER</span><span class="text-indigo-600 font-black text-lg">SAAS</span>
            </a>
            <button @click="mobileOpen = false" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-400">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5 text-sm text-gray-600"
             x-data="{}"
             @click.capture="if($event.target.closest('a[href]')) mobileOpen = false">
            <?php echo $__env->make('layouts.partials.nav-items', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </nav>
    </div>

    
    <div class="w-full lg:ps-[208px]">
        <div class="p-4 sm:p-6">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\Users\dede\saas-agendamento-barbiearia2026\resources\views/layouts/app.blade.php ENDPATH**/ ?>