<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\ImportedProduct;

class AlibabaImportController extends Controller
{
    protected $apiKey = 'fb176b23af6c85485122fa5224ea84be'; // À mettre dans .env !
    protected $scraperUrl = 'http://api.scraperapi.com';

    public function showForm()
    {
        return view('alibaba.form');
    }

    public function import(Request $request)
{
    $request->validate([
        'url' => 'required|url|regex:/alibaba\.com\/product-detail\//'
    ]);

    $client = new Client(['timeout' => 60]);

    try {
        $response = $client->get('http://api.scraperapi.com', [
            'query' => [
                'api_key' => $this->apiKey,
                'url' => $request->url,
                'render' => 'true',
                'country_code' => 'us',
            ],
        ]);

        $html = $response->getBody()->getContents();

        // === Extraction de TOUS les blocs JSON-LD ===
        if (!preg_match_all('/<script type="application\/ld\+json">([\s\S]*?)<\/script>/i', $html, $scriptMatches)) {
            return back()->withErrors(['error' => 'Aucun JSON-LD trouvé sur la page']);
        }

        $allJsonData = [];
        foreach ($scriptMatches[1] as $content) {
            $trimmed = trim($content);
            $decoded = json_decode($trimmed, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded)) {
                    $allJsonData = array_merge($allJsonData, $decoded);
                } else {
                    $allJsonData[] = $decoded;
                }
            }
        }

        if (empty($allJsonData)) {
            return back()->withErrors(['error' => 'Impossible de parser le JSON-LD']);
        }

        // dd($allJsonData); // ← Décommente pour voir tout le contenu

        // Recherche du bloc Product
        $productData = null;
        foreach ($allJsonData as $item) {
            if (isset($item['@type']) && $item['@type'] === 'Product') {
                $productData = $item;
                break;
            }
        }

        if (!$productData) {
            return back()->withErrors(['error' => 'Produit non trouvé dans le JSON-LD']);
        }

        // Extraction des images (depuis ImageObject ou fallback)
        $images = [];
        foreach ($allJsonData as $item) {
            if (isset($item['@type']) && $item['@type'] === 'ImageObject' && isset($item['contentUrl'])) {
                $images[] = $item['contentUrl'];
            }
        }

        if (empty($images) && isset($productData['image'])) {
            $images = is_array($productData['image']) ? $productData['image'] : [$productData['image']];
        }

        // MOQ : fallback texte simple
        $moq = 1;
        if (preg_match('/Minimum Order.*?(\d+)/i', $html, $m)) {
            $moq = (int)$m[1];
        }

        $product = [
            'source_url'    => $request->url,
            'title'         => $productData['name'] ?? 'Sans titre',
            'price_min'     => $productData['offers']['price'] ?? null,
            'price_max'     => $productData['offers']['price'] ?? null,
            'moq'           => $moq,
            'images'        => $images,
            'description'   => $productData['description'] ?? 'Aucune description',
            'supplier_name' => $productData['brand']['name'] ?? 'Inconnu',
            'supplier_url'  => null,
        ];

        // dd($productData); // ← Décommente pour voir le produit extrait

        return view('alibaba.preview', compact('product'));

    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Erreur ScraperAPI : ' . $e->getMessage()]);
    }
}

    public function save(Request $request)
    {
        $validated = $request->validate([
            'source_url'    => 'required|url|unique:imported_products,source_url',
            'title'         => 'required|string|max:255',
            'price_min'     => 'nullable|numeric',
            'price_max'     => 'nullable|numeric',
            'moq'           => 'nullable|integer',
            'images'        => 'required|array',
            'description'   => 'nullable|string',
            'supplier_name' => 'nullable|string',
        ]);

        $validated['images'] = json_encode($validated['images']);

        ImportedProduct::create($validated);

        return redirect()->route('alibaba.form')->with('success', 'Produit importé !');
    }
}
