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
    <!-- resources/views/alibaba/preview.blade.php -->
    <h1>{{ $product['title'] }}</h1>
    <p>Prix : ${{ $product['price_min'] }} @if($product['price_max'] && $product['price_max'] != $product['price_min']) - ${{ $product['price_max'] }}@endif</p>
    <p>MOQ : {{ $product['moq'] }} pièces</p>
    <p>Fournisseur : {{ $product['supplier_name'] }}</p>

    <div class="grid grid-cols-3 gap-4 my-8">
        @foreach($product['images'] as $img)
            <img src="{{ $img }}" alt="Produit" class="w-full rounded border">
        @endforeach
    </div>

    <div class="prose">
        {!! nl2br(e($product['description'])) !!}
    </div>

    <form action="{{ route('alibaba.save') }}" method="POST">
        @csrf
        @foreach($product as $key => $value)
            @if($key !== 'supplier_url')
                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? json_encode($value) : $value }}">
            @endif
        @endforeach
        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded">Sauvegarder dans la base</button>
    </form>
</body>
</html>
