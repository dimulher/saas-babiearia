<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-[#0B0F19]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Entrar') - GlowSystem</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Outfit', 'ui-sans-serif'] } } }
        }
    </script>
    <style>
        .glow-green { box-shadow: 0 0 40px rgba(22, 163, 74, 0.15); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="font-sans antialiased bg-[#0B0F19] min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-green-600/8 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-800/8 rounded-full blur-3xl"></div>
    </div>

    @php $errors = $errors ?? new \Illuminate\Support\ViewErrorBag; @endphp
    <div class="w-full max-w-lg px-4 relative z-10">
        @yield('content')
    </div>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
