<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>Document</title>
</head>
<body>
    <!-- resources/views/alibaba/form.blade.php -->
    <div class="max-w-2xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6">Importer un produit Alibaba</h1>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('alibaba.import') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-medium">URL du produit Alibaba</label>
                <input type="url" name="url" value="{{ old('url') }}"
                    class="w-full border rounded px-3 py-2" required placeholder="https://www.alibaba.com/product-detail/...">
            </div>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                Importer les détails
            </button>
        </form>
    </div>
</body>
</html>
