<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AlibabaApiService
{
    private $appKey;
    private $appSecret;
    private $accessToken;
    private $apiUrl;

    public function __construct()
    {
        $this->appKey = config('services.alibaba.app_key');
        $this->appSecret = config('services.alibaba.app_secret');
        $this->accessToken = config('services.alibaba.access_token');
        $this->apiUrl = config('services.alibaba.api_url');
    }

    /**
     * Récupère les détails d'un produit
     */
    public function getProduct($productId)
    {
        $method = 'alibaba.icbu.product.get';

        $params = [
            'product_id' => $productId,
            'language' => 'ENGLISH', // ou 'FRENCH'
        ];

        return $this->callApi($method, $params);
    }

    /**
     * Extrait l'ID produit depuis une URL Alibaba
     */
    public function extractProductIdFromUrl($url)
    {
        // Format URL: https://www.alibaba.com/product-detail/..._XXXXX.html
        // ou https://alibaba.com/product/XXXXX.html

        preg_match('/[_\/](\d{10,})/', $url, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Appel API générique avec signature
     */
    private function callApi($method, $params = [])
    {
        $timestamp = date('Y-m-d H:i:s');

        $systemParams = [
            'method' => $method,
            'app_key' => $this->appKey,
            'access_token' => $this->accessToken,
            'timestamp' => $timestamp,
            'format' => 'json',
            'v' => '2.0',
            'sign_method' => 'md5',
        ];

        // Fusion des paramètres
        $allParams = array_merge($systemParams, $params);

        // Génération de la signature
        $allParams['sign'] = $this->generateSignature($allParams);

        try {
            $response = Http::timeout(30)
                ->asForm()
                ->post($this->apiUrl, $allParams);

            if ($response->successful()) {
                $data = $response->json();

                // Vérification des erreurs API
                if (isset($data['error_response'])) {
                    throw new \Exception(
                        $data['error_response']['sub_msg'] ?? 'API Error'
                    );
                }

                return $data;
            }

            throw new \Exception('HTTP Error: ' . $response->status());

        } catch (\Exception $e) {
            Log::error('Alibaba API Error', [
                'method' => $method,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Génère la signature MD5 pour l'authentification
     */
    private function generateSignature($params)
    {
        // Retirer le signe s'il existe
        unset($params['sign']);

        // Trier par clé ASCII
        ksort($params);

        // Construire la chaîne
        $stringToBeSigned = $this->appSecret;

        foreach ($params as $key => $value) {
            if ($key !== 'sign' && $value !== '' && !is_array($value)) {
                $stringToBeSigned .= $key . $value;
            }
        }

        $stringToBeSigned .= $this->appSecret;

        // Générer le MD5 en majuscules
        return strtoupper(md5($stringToBeSigned));
    }

    /**
     * Parse les données du produit retournées
     */
    public function parseProductData($apiResponse)
    {
        $product = $apiResponse['alibaba_icbu_product_get_response']['result'] ?? null;

        if (!$product) {
            return null;
        }

        return [
            'id' => $product['product_id'] ?? null,
            'title' => $product['subject'] ?? null,
            'description' => $product['description'] ?? null,
            'price' => [
                'min' => $product['price_range']['start_price'] ?? null,
                'max' => $product['price_range']['end_price'] ?? null,
                'currency' => $product['price_range']['price_unit'] ?? 'USD',
            ],
            'moq' => $product['min_order'] ?? null, // Minimum Order Quantity
            'images' => $this->extractImages($product['image_list'] ?? []),
            'main_image' => $product['image_url'] ?? null,
            'category' => $product['category_name'] ?? null,
            'attributes' => $product['attributes'] ?? [],
            'shipping' => [
                'port' => $product['shipping_port'] ?? null,
                'time' => $product['shipping_time'] ?? null,
            ],
            'company' => [
                'name' => $product['company_name'] ?? null,
                'country' => $product['company_country'] ?? null,
            ],
            'url' => "https://www.alibaba.com/product-detail/{$product['product_id']}.html",
        ];
    }

    private function extractImages($imageList)
    {
        return array_map(function($image) {
            return is_array($image) ? ($image['url'] ?? $image) : $image;
        }, $imageList);
    }
}
