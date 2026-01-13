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
        $categories = Categorie::orderBy('label', 'asc')->paginate(12);
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

    // Créer une commande PayPal
    public function createPayPalOrder(Request $request)
    {
        try {
            Log::info('Création commande PayPal', $request->all());

            $request->validate([
                'amount' => 'required|numeric|min:0.01',
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

            $amountUSD = round($request->amount, 2);

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $amountUSD
                        ],
                        "description" => "Achat sur " . config('app.name')
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('checkout.cancel'),
                    "return_url" => route('checkout.success')
                ]
            ]);

            if (isset($order['id']) && $order['id'] != null) {
                // Stocker temporairement les infos de livraison
                if ($request->deliveryInfo) {
                    session(['pending_delivery_info' => $request->deliveryInfo]);
                }

                session(['paypal_order_id' => $order['id']]);

                return response()->json(['orderID' => $order['id']]);
            }

            Log::error('Erreur création commande PayPal', $order);
            return response()->json(['error' => 'Erreur lors de la création de la commande'], 500);

        } catch (\Exception $e) {
            Log::error('Exception createPayPalOrder: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    public function capturePayPalOrder(Request $request)
    {
        try {
            Log::info('Capture commande PayPal', $request->all());

            $request->validate([
                'orderID' => 'required',
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

            if (isset($result['status']) && $result['status'] == 'COMPLETED') {
                return $this->createOrderAfterPayment($result, $request);
            } else {
                Log::error('Capture PayPal échouée', $result);
                return response()->json([
                    'success' => false,
                    'error' => 'Le paiement n\'a pas pu être capturé'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Exception capturePayPalOrder: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du traitement: ' . $e->getMessage()
            ], 500);
        }
    }

    private function createOrderAfterPayment($paypalResult, $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Le panier est vide'], 400);
        }

        $totalCart = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
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
                          "Pays: {$pays->name}, " .
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

        // Créer la commande
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
            'total_amount' => $totalCart,
        ]);

        // Ajouter les produits
        foreach ($cart as $item) {
            $description = "Couleur: {$item['color']}, Niveau confort: {$item['niveau_confort']}, Poids: {$item['poids']} kg";

            Commander::create([
                'commande_id' => $commande->id,
                'produit_id' => $item['id'],
                'quantity' => $item['quantity'],
                'description_produit' => $description,
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

        // Envoyer les emails
        $this->sendOrderEmails($commande, $deliveryData);

        // Nettoyer la session
        session()->forget('cart');
        session()->forget('pending_delivery_info');
        session()->forget('delivery_info');

        return response()->json([
            'success' => true,
            'order_id' => $commande->id,
            'order_code' => $code,
            'transaction_id' => $paypalResult['id'],
            'message' => 'Commande enregistrée avec succès'
        ]);
    }

    private function sendOrderEmails($order, $deliveryData)
    {
        try {
            $user = Auth::user();

            // Email 1: Confirmation de commande
            Mail::to($user->email)->send(new OrderConfirmationEmail($order));

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




}
