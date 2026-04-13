<?php

namespace App\Http\Controllers;

use App\Helpers\FrontHelper;
use App\Http\Requests\SendContactRequest;
use App\Mail\DeliveryProcessingEmail;
use App\Mail\OrderConfirmationEmail;
use App\Mail\receiveContactMail;
use App\Mail\sendContactMail;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\Commander;
use App\Models\Currency;
use App\Models\Pays;
use App\Models\Produit;
use App\Models\SousCategorie;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class SumbaawaController extends Controller
{
    public function categoriesSingle(Categorie $categorie)
    {
        $subcategoryIds = $categorie->sousCategories->pluck('id')->toArray();

        $produits = Produit::whereIn('sous_categorie_id', $subcategoryIds)
            ->with(['sousCategorie', 'images'])
            ->paginate(12); // Remettez à 12 ou votre valeur standard, au lieu de 2 pour les tests

        // Définir l'URL de base pour les liens de pagination sur la route de filtrage
        $produits->setPath(route('categories.filter', $categorie));

        // Ajouter les paramètres subcategories[] pour préserver l'état "Voir tout"
        $produits->appends(['subcategories' => $subcategoryIds]);

        return view('front.pages.categories.single', compact('categorie', 'produits'));
    }

    public function categoriesPage()
    {
        $categories = Categorie::whereNotIn('slug', [
                'le-bazar-de-lelectronique',
                'cest-ma-voiture'
            ])
            ->orderBy('label', 'asc')
            ->paginate(12);
        return view('front.pages.categories.index', compact('categories'));
    }

    public function contact()
    {
        return view('front.pages.contact');
    }

    public function sousCategoriesDetail(SousCategorie $sousCategorie)
    {
        $produits = Produit::where('sous_categorie_id', $sousCategorie->id)
            ->with(['sousCategorie', 'images'])
            ->paginate(12);

        // Définir l'URL de base pour les liens de pagination
        // $produits->setPath(route('sousCategories.single', $sousCategorie));

        return view('front.pages.sousCategories.single', compact('sousCategorie', 'produits'));
    }

    public function filter(Request $request, Categorie $categorie)
    {
        try {
            // Récupère les IDs des sous-catégories depuis la requête
            $subcategoryIds = $request->input('subcategories', []);

            // Convertit en tableau si ce n'est pas déjà le cas
            if (!is_array($subcategoryIds)) {
                $subcategoryIds = explode(',', $subcategoryIds);
            }

            // Vérifie que les IDs sont valides (entiers)
            $subcategoryIds = array_filter($subcategoryIds, 'is_numeric');

            // Charge les produits avec les relations nécessaires
            if (empty($subcategoryIds)) {
                $produits = Produit::whereIn('sous_categorie_id', $categorie->sousCategories->pluck('id'))
                    ->with(['sousCategorie', 'images'])
                    ->paginate(12);
            } else {
                $produits = Produit::whereIn('sous_categorie_id', $subcategoryIds)
                    ->with(['sousCategorie', 'images'])
                    ->paginate(12);
            }

            // Retourne la vue partielle pour les produits et la pagination
            return view('front.partials.produits', compact('produits'))->render();
        } catch (\Exception $e) {
            Log::error('Erreur dans filter: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors du filtrage des produits'], 500);
        }
    }

    public function produitDetail(Produit $produit)
    {
        $produit->load('sousCategorie.categorie', 'images');
        $relatedProduits = Produit::where('sous_categorie_id', $produit->sous_categorie_id)
            ->where('id', '!=', $produit->id)
            ->with(['sousCategorie', 'images'])
            ->take(6)
            ->get();

        return view('front.pages.produits.single', compact('produit', 'relatedProduits'));
    }

    // public function single(Produit $produit)
    // {
    //     $produit->load('sousCategorie.categorie', 'images');
    //     $relatedProduits = Produit::where('sous_categorie_id', $produit->sous_categorie_id)
    //         ->where('id', '!=', $produit->id)
    //         ->with(['sousCategorie', 'images'])
    //         ->take(6)
    //         ->get();

    //     return view('front.pages.produits.single', compact('produit', 'relatedProduits'));
    // }

    public function addToCart(Request $request)
    {
        $productData = $request->input('product');
        $cart = session()->get('cart', []);

        // Déterminer la couleur à utiliser
        $colors = isset($productData['color']) && $productData['color'] ? array_filter(array_map('trim', explode(',', $productData['color']))) : [];
        $selectedColor = !empty($colors) ? reset($colors) : 'Néant'; // Prendre la couleur sélectionnée ou la première couleur

        $produit = Produit::find($productData['id']);
        if (isset($cart[$productData['id']])) {
            $cart[$productData['id']]['quantity'] += ($productData['quantity'] ?? 1);
            $cart[$productData['id']]['color'] = $selectedColor; // Mettre à jour la couleur si nécessaire
        } else {
            $cart[$productData['id']] = [
                'id' => $productData['id'],
                'name' => $productData['name'],
                'image_main' => $productData['image_main'],
                'color' => $selectedColor, // Stocker uniquement la couleur sélectionnée
                'niveau_confort' => $productData['niveau_confort'] ?? null,
                'poids' => $productData['poids'],
                'price' => $productData['price'],
                'quantity' => $productData['quantity'] ?? 1,
                'product_url' => route('produits.single', $produit->slug),
            ];
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $freeShippingThreshold = 50;
        $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);

        session()->put('cart', $cart);

        return response()->json([
            'cart' => [
                'items' => array_values($cart),
                'total' => number_format($total, 2),
                'free_shipping_threshold' => $freeShippingThreshold,
                'progress_percentage' => $progressPercentage
            ]
        ]);
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            if ($quantity <= 0) {
                unset($cart[$productId]);
            }
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $freeShippingThreshold = 50;
        $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);

        session()->put('cart', $cart);

        return response()->json([
            'cart' => [
                'items' => array_values($cart),
                'total' => number_format($total, 2),
                'free_shipping_threshold' => $freeShippingThreshold,
                'progress_percentage' => $progressPercentage
            ]
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $freeShippingThreshold = 50;
        $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);

        session()->put('cart', $cart);

        return response()->json([
            'cart' => [
                'items' => array_values($cart),
                'total' => number_format($total, 2),
                'free_shipping_threshold' => $freeShippingThreshold,
                'progress_percentage' => $progressPercentage
            ]
        ]);
    }

    public function clearCart()
    {
        session()->forget('cart');

        return response()->json([
            'cart' => [
                'items' => [],
                'total' => '0.00',
                'free_shipping_threshold' => 50,
                'progress_percentage' => 0
            ]
        ]);
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $cartItems = array_map(function ($item) {
            $item['product_url'] = route('produits.single', Str::slug($item['name']));
            return $item;
        }, $cart);

        // dd($cartItems);
        return view('front.pages.cart', [
            'cart' => [
                'items' => array_values($cartItems),
                'total' => number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2),
                'free_shipping_threshold' => 50,
                'progress_percentage' => min((array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)) / 50) * 100, 100)
            ]
        ]);
    }

    public function getCart()
    {
        try {
            $cart = session()->get('cart', []);
            $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
            $freeShippingThreshold = 50;
            $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);

            return response()->json([
                'cart' => [
                    'items' => array_values($cart),
                    'total' => number_format($total, 2),
                    'free_shipping_threshold' => $freeShippingThreshold,
                    'progress_percentage' => $progressPercentage
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans getCart: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    public function checkout()
    {
        // Récupérer le panier depuis la session
        $cart = session('cart');

        // Si le panier n'existe pas ou est vide → redirection accueil
        if (empty($cart) || !is_array($cart)) {
            return redirect()->route('index');
        }

        // Calculer le poids total (somme des poids unitaires * quantité)
        $totalWeight = array_sum(array_map(fn($item) => $item['poids'] * $item['quantity'], $cart));

        // Calculer le total du panier (somme des prix unitaires * quantité)
        $totalCart = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // Retourner la vue avec les données
        return view('front.pages.checkout', compact('cart', 'totalWeight', 'totalCart'));
    }

    /**
     * Convertir un montant d'une devise vers USD
     *
     * @param float $amount Montant à convertir
     * @param string $fromCurrency Code de la devise source
     * @return float Montant en USD
     */
    private function convertToUSD($amount, $fromCurrency = 'XOF')
    {
        // Si déjà en USD, retourner le montant
        if ($fromCurrency === 'USD') {
            return round($amount, 2);
        }

        // Récupérer le taux de change USD depuis la BD
        $usdCurrency = Currency::where('code', 'USD')->first();

        if (!$usdCurrency) {
            // Fallback sur un taux par défaut si non trouvé
            Log::warning('Devise USD non trouvée dans la BD, utilisation du taux par défaut');
            return round($amount / 600, 2); // Taux par défaut XOF -> USD
        }

        $usdRate = $usdCurrency->exchange_rate;

        if ($fromCurrency === 'XOF') {
            // Conversion directe XOF vers USD
            // Formule : montant_xof / taux_usd
            // Car les taux sont basés sur XOF (1 XOF = X unités de devise)
            return round($amount / $usdRate, 2);
        } else {
            // Pour les autres devises (EUR, GBP, etc.), passer d'abord par XOF
            $fromCurrencyObj = Currency::where('code', $fromCurrency)->first();

            if (!$fromCurrencyObj) {
                Log::warning("Devise {$fromCurrency} non trouvée, conversion directe");
                return round($amount / $usdRate, 2);
            }

            $fromRate = $fromCurrencyObj->exchange_rate;

            // Étape 1: Convertir vers XOF
            // Formule : montant_devise * taux_devise = montant_xof
            $amountInXOF = $amount * $fromRate;

            // Étape 2: Convertir XOF vers USD
            // Formule : montant_xof / taux_usd = montant_usd
            return round($amountInXOF / $usdRate, 2);
        }
    }

    /**
     * Convertir USD vers XOF (pour vérification)
     *
     * @param float $amountUSD Montant en USD
     * @return float Montant en XOF
     */
    private function convertUSDToXOF($amountUSD)
    {
        $usdCurrency = Currency::where('code', 'USD')->first();

        if (!$usdCurrency) {
            return round($amountUSD * 600, 2); // Taux par défaut
        }

        // Formule : montant_usd * taux_usd = montant_xof
        return round($amountUSD * $usdCurrency->exchange_rate, 2);
    }

    public function createPayPalOrder(Request $request)
    {
        try {
            Log::info('Création commande PayPal', $request->all());

            $request->validate([
                'amount' => 'required|numeric|min:0.01', // Montant en USD
                'currency' => 'nullable|string', // Code de la devise utilisée par le client
                'amountInXOF' => 'nullable|numeric', // Montant en XOF (devise de base)
                'requiresDelivery' => 'required|boolean',
                'deliveryInfo' => 'required_if:requiresDelivery,true'
            ]);

            $provider = new PayPalClient;

            $config = [
                'mode' => config('services.paypal.mode', 'sandbox'),
                'sandbox' => [
                    'client_id' => config('services.paypal.sandbox.client_id'),
                    'client_secret' => config('services.paypal.sandbox.client_secret'),
                    'app_id' => 'APP-80W284485P519543T',
                ],
                'live' => [
                    'client_id' => config('services.paypal.live.client_id'),
                    'client_secret' => config('services.paypal.live.client_secret'),
                    'app_id' => config('services.paypal.live.app_id'),
                ],
                'payment_action' => 'Sale',
                'currency' => 'USD',
                'notify_url' => '',
                'locale' => 'en_US',
                'validate_ssl' => true,
            ];

            $provider->setApiCredentials($config);
            $token = $provider->getAccessToken();

            // Récupérer ou calculer le montant en USD
            $amountUSD = $request->amount;
            $currency = $request->currency ?? 'XOF';
            $amountInXOF = $request->amountInXOF;

            // Si le montant en XOF est fourni, vérifier la conversion
            if ($amountInXOF) {
                // Recalculer le montant USD depuis XOF pour vérification
                $calculatedUSD = $this->convertToUSD($amountInXOF, 'XOF');

                // Tolérance de 0.5% pour les différences d'arrondi
                $tolerance = $calculatedUSD * 0.005;

                if (abs($calculatedUSD - $amountUSD) > $tolerance) {
                    Log::warning('Écart de conversion détecté', [
                        'montant_fourni_usd' => $amountUSD,
                        'montant_calcule_usd' => $calculatedUSD,
                        'montant_xof' => $amountInXOF,
                        'devise' => $currency
                    ]);

                    // Utiliser le montant recalculé pour plus de sécurité
                    $amountUSD = $calculatedUSD;
                }
            } else {
                // Si pas de montant XOF fourni, calculer depuis le panier
                $cart = session('cart', []);
                $totalCart = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
                $amountInXOF = $totalCart;

                // Convertir XOF vers USD
                $amountUSD = $this->convertToUSD($totalCart, 'XOF');
            }

            // Arrondir à 2 décimales
            $amountUSD = round($amountUSD, 2);

            // Vérifier le montant minimum
            if ($amountUSD < 0.01) {
                return response()->json([
                    'error' => 'Le montant est trop faible pour être traité par PayPal (minimum $0.01)'
                ], 400);
            }

            Log::info('Montants de la commande', [
                'montant_xof' => $amountInXOF,
                'montant_usd' => $amountUSD,
                'devise_client' => $currency
            ]);

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($amountUSD, 2, '.', '')
                        ],
                        "description" => "Achat sur " . config('app.name'),
                        "custom_id" => json_encode([
                            'amount_xof' => $amountInXOF,
                            'currency' => $currency,
                            'user_id' => Auth::id()
                        ])
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('checkout.cancel'),
                    "return_url" => route('checkout.success')
                ]
            ]);

            if (isset($order['id']) && $order['id'] != null) {
                // Stocker les informations dans la session
                session([
                    'paypal_order_id' => $order['id'],
                    'paypal_amount_usd' => $amountUSD,
                    'paypal_amount_xof' => $amountInXOF,
                    'paypal_currency' => $currency,
                    'pending_delivery_info' => $request->deliveryInfo,
                    'requires_delivery' => $request->requiresDelivery
                ]);

                Log::info('Commande PayPal créée avec succès', [
                    'order_id' => $order['id'],
                    'amount_usd' => $amountUSD,
                    'amount_xof' => $amountInXOF
                ]);

                return response()->json([
                    'success' => true,
                    'orderID' => $order['id']
                ]);
            }

            Log::error('Erreur création commande PayPal', $order);
            return response()->json([
                'error' => 'Erreur lors de la création de la commande'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Exception createPayPalOrder: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function capturePayPalOrder(Request $request)
    {
        try {
            Log::info('Capture commande PayPal', $request->all());

            $request->validate([
                'orderID' => 'required',
                'currency' => 'nullable|string',
                'amountInXOF' => 'nullable|numeric',
                'requiresDelivery' => 'required|boolean',
                'deliveryInfo' => 'required_if:requiresDelivery,true'
            ]);

            $provider = new PayPalClient;

            $config = [
                'mode' => config('services.paypal.mode', 'sandbox'),
                'sandbox' => [
                    'client_id' => config('services.paypal.sandbox.client_id'),
                    'client_secret' => config('services.paypal.sandbox.client_secret'),
                    'app_id' => 'APP-80W284485P519543T',
                ],
                'live' => [
                    'client_id' => config('services.paypal.live.client_id'),
                    'client_secret' => config('services.paypal.live.client_secret'),
                    'app_id' => config('services.paypal.live.app_id'),
                ],
                'payment_action' => 'Sale',
                'currency' => 'USD',
                'notify_url' => '',
                'locale' => 'en_US',
                'validate_ssl' => true,
            ];

            $provider->setApiCredentials($config);
            $provider->getAccessToken();

            $result = $provider->capturePaymentOrder($request->orderID);

            Log::info('Résultat capture PayPal', $result);

            if (isset($result['status']) && $result['status'] == 'COMPLETED') {
                // Récupérer les infos de la session
                $amountInXOF = $request->amountInXOF ?? session('paypal_amount_xof');
                $amountUSD = session('paypal_amount_usd');
                $currency = $request->currency ?? session('paypal_currency', 'XOF');

                // Vérification de sécurité : comparer avec le montant PayPal
                $paypalAmountUSD = floatval($result['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0);

                if ($paypalAmountUSD > 0 && abs($paypalAmountUSD - $amountUSD) > 0.02) {
                    Log::warning('Écart entre montant session et PayPal', [
                        'session_usd' => $amountUSD,
                        'paypal_usd' => $paypalAmountUSD
                    ]);

                    // Recalculer le montant XOF depuis le montant PayPal
                    $amountInXOF = $this->convertUSDToXOF($paypalAmountUSD);
                    $amountUSD = $paypalAmountUSD;
                }

                // Si pas de montant XOF, le calculer
                if (!$amountInXOF) {
                    $amountInXOF = $this->convertUSDToXOF($amountUSD);
                }

                Log::info('Montants finaux pour la commande', [
                    'amount_xof' => $amountInXOF,
                    'amount_usd' => $amountUSD,
                    'currency' => $currency,
                    'paypal_amount' => $paypalAmountUSD
                ]);

                return $this->createOrderAfterPayment($result, $request, [
                    'amount_xof' => $amountInXOF,
                    'amount_usd' => $amountUSD,
                    'currency' => $currency
                ]);
            } else {
                Log::error('Capture PayPal échouée', $result);
                return response()->json([
                    'success' => false,
                    'error' => 'Le paiement n\'a pas pu être capturé'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Exception capturePayPalOrder: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du traitement: ' . $e->getMessage()
            ], 500);
        }
    }

    private function createOrderAfterPayment($paypalResult, $request, $amounts = [])
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Le panier est vide'], 400);
        }

        // Calculer le total du panier (toujours en XOF dans la session)
        $totalCart = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // Utiliser le montant fourni ou celui calculé depuis le panier
        $amountInXOF = $amounts['amount_xof'] ?? $totalCart;
        $amountUSD = $amounts['amount_usd'] ?? null;
        $currency = $amounts['currency'] ?? 'XOF';

        // Vérification de cohérence
        $tolerance = $totalCart * 0.01; // 1% de tolérance
        if (abs($amountInXOF - $totalCart) > $tolerance) {
            Log::warning('Écart entre total panier et montant payé', [
                'total_panier' => $totalCart,
                'montant_paye_xof' => $amountInXOF,
                'difference' => abs($amountInXOF - $totalCart)
            ]);

            // Utiliser le montant du panier pour plus de sécurité
            $amountInXOF = $totalCart;
        }

        $code = 'CMD-' . strtoupper(Str::random(8));
        $address = null;
        $typeId = null;
        $deliveryMethod = null;
        $deliveryInfo = null;

        // Récupérer les infos de livraison
        $deliveryData = $request->deliveryInfo ?? session('pending_delivery_info');

        if ($deliveryData) {
            $deliveryMethod = $deliveryData['deliveryMethod'] ?? null;
            $typeId = $deliveryData['deliveryType'] ?? null;

            if ($deliveryMethod === 'tinda_awa') {
                $pays = Pays::find($deliveryData['country']);
                $address = "Livraison Tinda Awa - " .
                          "Pays: " . ($pays ? $pays->name : 'N/A') . ", " .
                          "Ville: {$deliveryData['city']}, " .
                          "Adresse: {$deliveryData['address']}, " .
                          "Destinataire: {$deliveryData['recipientName']}, " .
                          "Tél: {$deliveryData['recipientPhone']}";
            } else {
                $address = "Livraison Cargo - " .
                          "Ville: {$deliveryData['city']}, " .
                          "Adresse: {$deliveryData['address']}, " .
                          "Contact: {$deliveryData['contactName']}, " .
                          "Tél: {$deliveryData['contactPhone']}";
            }

            $deliveryInfo = json_encode($deliveryData);
        }

        // Créer la commande avec les informations de devise
        $commande = Commande::create([
            'user_id' => $userId,
            'code' => $code,
            'address' => $address,
            'type_id' => $typeId,
            'delivery_method' => $deliveryMethod,
            'delivery_info' => $deliveryInfo,
            'payment_method' => 'paypal',
            'payment_status' => 'paid',
            'status' => 'pending',
            'payment_id' => $paypalResult['id'],
            'payment_email' => $paypalResult['payer']['email_address'] ?? null,
            'total_amount' => $amountInXOF, // Toujours en XOF (devise de base)
            'amount_usd' => $amountUSD, // Montant en USD pour référence
            'currency' => $currency, // Devise utilisée par le client
        ]);

        Log::info('Commande créée', [
            'id' => $commande->id,
            'code' => $code,
            'total_xof' => $amountInXOF,
            'amount_usd' => $amountUSD,
            'currency' => $currency
        ]);

        // Ajouter les produits
        foreach ($cart as $item) {
            $description = "Couleur: " . ($item['color'] ?? 'N/A') .
                          ", Niveau confort: " . ($item['niveau_confort'] ?? 'N/A') .
                          ", Poids: " . ($item['poids'] ?? 0) . " kg";

            Commander::create([
                'commande_id' => $commande->id,
                'produit_id' => $item['id'],
                'quantity' => $item['quantity'],
                'description_produit' => $description,
                'unit_price' => $item['price'], // Prix unitaire en XOF
                'total_price' => $item['price'] * $item['quantity'], // Total en XOF
            ]);
        }

        // Envoyer les emails
        try {
            $this->sendOrderEmails($commande, $deliveryData);
        } catch (\Exception $e) {
            Log::error('Erreur envoi email commande: ' . $e->getMessage());
            // Ne pas bloquer la création de commande si l'email échoue
        }

        // Nettoyer la session
        session()->forget([
            'cart',
            'pending_delivery_info',
            'delivery_info',
            'paypal_order_id',
            'paypal_amount_usd',
            'paypal_amount_xof',
            'paypal_currency',
            'requires_delivery'
        ]);

        return response()->json([
            'success' => true,
            'order_id' => $commande->id,
            'order_code' => $code,
            'transaction_id' => $paypalResult['id'],
            'amount_xof' => $amountInXOF,
            'amount_usd' => $amountUSD,
            'currency' => $currency,
            'message' => 'Commande enregistrée avec succès'
        ]);
    }

    private function sendOrderEmails($order, $deliveryData)
    {
        try {
            $user = Auth::user();

            // Email 1: Confirmation de commande
            // Mail::to($user->email)->send(new OrderConfirmationEmail($order));

            // Email 2: Processus de livraison
            Mail::to($user->email)->send(new DeliveryProcessingEmail($order, $deliveryData));

        } catch (\Exception $e) {
            Log::error('Erreur envoi email: ' . $e->getMessage());
        }
    }


    public function confirmation(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect()->route('checkout')->with('error', 'Commande non trouvée');
        }

        // Option 1: Utiliser la relation commandesProduits (nom correct)
        $order = Commande::with(['commandesProduits.produit', 'user', 'type'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();


        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Commande non trouvée');
        }

        return view('front.pages.checkout-success', compact('order'));
    }

    public function checkoutSuccess(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            // Essayer de récupérer la dernière commande
            $order = Commande::with(['commandesProduits.produit', 'user', 'type'])
                ->where('user_id', Auth::id())
                ->latest()
                ->first();
        } else {
            $order = Commande::with(['commandesProduits.produit', 'user', 'type'])
                ->where('id', $orderId)
                ->where('user_id', Auth::id())
                ->first();
        }

        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Commande non trouvée');
        }

        return view('front.pages.checkout-success', compact('order'));
    }

    public function checkoutCancel(Request $request)
    {
        return view('front.pages.checkout-cancel');
    }

    // public function checkoutProcess(Request $request)
    // {
    //     $data = $request->all();
    //     $cart = session('cart', []);

    //     if (empty($cart)) {
    //         return response()->json(['error' => 'Le panier est vide'], 400);
    //     }

    //     $userId = Auth::id();
    //     if (!$userId) {
    //         return response()->json(['error' => 'Utilisateur non authentifié'], 401);
    //     }

    //     $code = 'CMD-' . strtoupper(Str::random(8));
    //     $address = null;
    //     $typeId = null;

    //     if ($data['requiresDelivery'] && isset($data['deliveryInfo'])) {
    //         $delivery = $data['deliveryInfo'];
    //         $pays = Pays::findOrFail($delivery['country']);
    //         $address = "Pays: {$pays->name}, Ville: {$delivery['city']}, Adresse: {$delivery['address']}, Code Postal: {$delivery['postalCode']}";
    //         $typeId = $delivery['deliveryType'];
    //     }

    //     $commande = Commande::create([
    //         'user_id' => $userId,
    //         'code' => $code,
    //         'address' => $address,
    //         'type_id' => $typeId,
    //     ]);

    //     foreach ($cart as $item) {
    //         $description = "Couleur: {$item['color']}, Niveau de confort: {$item['niveau_confort']}, Poids: {$item['poids']} kg";

    //         Commander::create([
    //             'commande_id' => $commande->id,
    //             'produit_id' => $item['id'],
    //             'quantity' => $item['quantity'],
    //             'description_produit' => $description,
    //         ]);
    //     }

    //     // Vider le panier
    //     session()->forget('cart');

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Commande enregistrée avec succès'
    //     ]);
    // }

    public function storeDeliveryInfo(Request $request)
    {
        $data = $request->validate([
            'deliveryMethod' => 'required|in:tinda_awa,cargo',
            'deliveryType' => 'required',
            'city' => 'required',
            'address' => 'required',
        ]);

        // Ajouter des champs spécifiques selon la méthode
        if ($data['deliveryMethod'] === 'tinda_awa') {
            $request->validate([
                'country' => 'required',
                'recipientName' => 'required',
                'recipientPhone' => 'required',
            ]);

            $data['country'] = $request->country;
            $data['recipientName'] = $request->recipientName;
            $data['recipientPhone'] = $request->recipientPhone;
        } else {
            $request->validate([
                'contactName' => 'required',
                'contactPhone' => 'required',
            ]);

            $data['contactName'] = $request->contactName;
            $data['contactPhone'] = $request->contactPhone;
        }

        session(['delivery_info' => $data]);

        return response()->json(['success' => true]);
    }

    public function clearDeliveryInfo(Request $request)
    {
        session()->forget('delivery_info');

        return response()->json(['success' => true]);
    }

    public function addWishlist(Request $request, Produit $produit)
    {
        $user = Auth::user();

        // Vérifier si le produit est déjà dans la wishlist
        $existingWishlistItem = $user->wishlists()->where('produit_id', $produit->id)->first();
        if ($existingWishlistItem) {
            return back()->with('wishlist_info', 'Produit déjà dans la liste des favoris.');
        }

        // Ajouter le produit à la wishlist
        $user->wishlists()->create(['produit_id' => $produit->id]);

        return back()->with('wishlist_success', 'Produit ajouté à la liste des favoris avec succès.');
    }

    public function myWishlist()
    {
        $user = Auth::user();
        $wishlists = Wishlist::where('user_id', $user->id)->paginate(12);

        return view('front.pages.wishlists', compact('wishlists'));
    }

    public function search(Request $request)
    {
        $query = $request->query('q', '');

        $produits = Produit::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhereHas('sousCategorie', function ($q) use ($query) {
                        $q->where('label', 'LIKE', "%{$query}%")
                            ->orWhereHas('categorie', function ($q) use ($query) {
                                $q->where('label', 'LIKE', "%{$query}%");
                            });
                    });
            })
            ->with(['sousCategorie', 'sousCategorie.categorie'])
            ->paginate(12);

        return view('front.pages.search_results', [
            'produits' => $produits,
            'query' => $query,
        ]);
    }

    public function contactSend(SendContactRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ];


        // Mail::to(FrontHelper::getSetting()->company_mail)->send(new sendContactMail($data));
        Mail::to('adebissiimorou@gmail.com')->send(new sendContactMail($data));

        Mail::to($data['email'])->send(new receiveContactMail($data));
        return back()->with('contact_success', 'Message envoyé avec succès!');
    }

    public function switch(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|exists:currencies,code'
        ]);

        $currency = Currency::where('code', $request->currency_code)
                           ->where('is_active', true)
                           ->firstOrFail();

        // 1. Définir le cookie (1 an de validité)
        cookie()->queue('currency_code', $currency->code, 525600);

        // 2. Si l'utilisateur est connecté, sauvegarder en BD
        if (auth()->check()) {
            auth()->user()->update([
                'preferred_currency_id' => $currency->id
            ]);
        }

        return response()->json([
            'success' => true,
            'currency' => $currency,
            'message' => 'Devise changée avec succès'
        ]);
    }

    public function available()
    {
        return Currency::where('is_active', true)->get();
    }

    public function formatAmount($amount)
    {
        return response()->json([
            'formatted' => FrontHelper::format_currency($amount),
            'raw' => FrontHelper::convert_currency($amount),
            'currency' => FrontHelper::current_currency()
        ]);
    }

    public function getCurrentCurrency()
    {
        $currency = FrontHelper::current_currency();
        return response()->json([
            'code' => $currency->code,
            'symbol' => $currency->symbol,
            'exchange_rate' => $currency->exchange_rate,
            'decimals' => $currency->code === 'XOF' ? 0 : 2
        ]);
    }

    /**
     * POST /wallet/pay-order
     * Payer une commande avec le solde du portefeuille
     */
    public function payWithWallet(Request $request)
    {
        try {
            Log::info('Paiement portefeuille', $request->all());

            $request->validate([
                'amountInXOF'    => 'required|numeric|min:1',
                'requiresDelivery' => 'required|boolean',
                'deliveryInfo'   => 'required_if:requiresDelivery,true',
            ]);

            $user       = Auth::user();
            $amountXOF  = (float) $request->amountInXOF;

            // ── Vérifier le solde ───────────────────────────────────────────────
            if ($user->solde < $amountXOF) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Solde insuffisant. Veuillez recharger votre portefeuille ou utiliser autres moyens de paiement.',
                ], 400);
            }

            // ── Vérifier le panier ──────────────────────────────────────────────
            $cart = session('cart', []);
            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Le panier est vide.',
                ], 400);
            }

            // ── Vérifier la cohérence du montant ────────────────────────────────
            $totalCart = array_sum(
                array_map(fn($item) => $item['price'] * $item['quantity'], $cart)
            );

            $tolerance = $totalCart * 0.01; // tolérance 1%
            if (abs($amountXOF - $totalCart) > $tolerance) {
                Log::warning('Wallet pay: écart montant', [
                    'total_panier' => $totalCart,
                    'montant_recu' => $amountXOF,
                ]);
                // On utilise le total du panier (source de vérité côté serveur)
                $amountXOF = $totalCart;
            }

            // ── Défalquer le solde ──────────────────────────────────────────────
            // decrement() est atomique en SQL : UPDATE users SET solde = solde - X WHERE id = Y
            $user->decrement('solde', $amountXOF);

            // ── Construire les infos de livraison ───────────────────────────────
            $deliveryData   = $request->deliveryInfo ?? session('pending_delivery_info');
            $deliveryMethod = null;
            $typeId         = null;
            $address        = null;
            $deliveryInfo   = null;

            if ($deliveryData) {
                $deliveryMethod = $deliveryData['deliveryMethod'] ?? null;
                $typeId         = $deliveryData['deliveryType'] ?? null;

                if ($deliveryMethod === 'tinda_awa') {
                    $pays    = \App\Models\Pays::find($deliveryData['country']);
                    $address = "Livraison Tinda Awa - "
                            . "Pays: "         . ($pays ? $pays->name : 'N/A') . ", "
                            . "Ville: "        . $deliveryData['city']          . ", "
                            . "Adresse: "      . $deliveryData['address']       . ", "
                            . "Destinataire: " . $deliveryData['recipientName'] . ", "
                            . "Tél: "          . $deliveryData['recipientPhone'];
                } else {
                    $address = "Livraison Cargo - "
                            . "Ville: "    . $deliveryData['city']         . ", "
                            . "Adresse: "  . $deliveryData['address']      . ", "
                            . "Contact: "  . $deliveryData['contactName']  . ", "
                            . "Tél: "      . $deliveryData['contactPhone'];
                }

                $deliveryInfo = json_encode($deliveryData);
            }

            // ── Créer la commande ───────────────────────────────────────────────
            $code = 'CMD-' . strtoupper(Str::random(8));

            // Identifiant de transaction portefeuille (pas une vraie transaction bancaire)
            $walletTransactionId = 'WALLET-' . $user->id . '-' . now()->format('YmdHis');

            $commande = Commande::create([
                'user_id'        => $user->id,
                'code'           => $code,
                'address'        => $address,
                'type_id'        => $typeId,
                'delivery_method'=> $deliveryMethod,
                'delivery_info'  => $deliveryInfo,
                'payment_method' => 'wallet',
                'payment_status' => 'paid',
                'status'         => 'pending',
                'payment_id'     => $walletTransactionId,
                'payment_email'  => $user->email,
                'total_amount'   => $amountXOF,   // toujours en XOF
                'amount_usd'     => null,          // pas de conversion USD pour le wallet
                'currency'       => 'XOF',
            ]);

            Log::info('Commande wallet créée', [
                'id'          => $commande->id,
                'code'        => $code,
                'amount_xof'  => $amountXOF,
                'solde_avant' => $user->solde + $amountXOF,
                'solde_apres' => $user->fresh()->solde,
            ]);

            // ── Ajouter les produits ────────────────────────────────────────────
            foreach ($cart as $item) {
                $description = "Couleur: "        . ($item['color']          ?? 'N/A') . ", "
                            . "Niveau confort: " . ($item['niveau_confort'] ?? 'N/A') . ", "
                            . "Poids: "          . ($item['poids']          ?? 0)     . " kg";

                Commander::create([
                    'commande_id'         => $commande->id,
                    'produit_id'          => $item['id'],
                    'quantity'            => $item['quantity'],
                    'description_produit' => $description,
                    'unit_price'          => $item['price'],
                    'total_price'         => $item['price'] * $item['quantity'],
                ]);
            }

            // ── Email ───────────────────────────────────────────────────────────
            try {
                $this->sendOrderEmails($commande, $deliveryData);
            } catch (\Exception $e) {
                Log::error('Wallet pay: erreur email: ' . $e->getMessage());
            }

            // ── Nettoyer la session ─────────────────────────────────────────────
            session()->forget([
                'cart',
                'pending_delivery_info',
                'delivery_info',
            ]);

            return response()->json([
                'success'     => true,
                'order_id'    => $commande->id,
                'order_code'  => $code,
                'amount_xof'  => $amountXOF,
                'new_solde'   => $user->fresh()->solde,
                'message'     => 'Commande enregistrée avec succès',
            ]);

        } catch (\Exception $e) {
            Log::error('payWithWallet exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'error'   => 'Erreur serveur : ' . $e->getMessage(),
            ], 500);
        }
    }





}
