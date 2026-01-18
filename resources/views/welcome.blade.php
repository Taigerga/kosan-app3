<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kosan App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di Kosan App</h1>
        <p class="text-gray-600 mb-8">Platform pencarian kos terbaik</p>
        
        <div class="space-x-4">
            <a href="{{ route('login') }}" 
               class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                Login
            </a>
            <a href="{{ route('register') }}" 
               class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition">
                Daftar
            </a>
        </div>

        @if(session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>
</body>
</html>