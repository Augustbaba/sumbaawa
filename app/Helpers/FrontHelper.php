<?php

namespace App\Helpers;

use App;
use App\Models\Service;
use App\Models\Encashment;
use App\Models\Customer;
use App\Models\Enterprise;
use App\Models\Actuality;
use App\Models\Categorie;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contribute;
use App\Models\Currency;
use App\Models\Departement;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Realisation;
use App\Models\Phone;
use App\Models\Refund;
use App\Models\Setting;
use App\Models\Email;
use App\Models\Faq;
use App\Models\FinancingMethod;
use App\Models\Formation;
use App\Models\Partner;
use App\Models\Pays;
use App\Models\Produit;
use App\Models\Project;
use App\Models\Realization;
use App\Models\Type;
use App\Models\TypeContribution;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class FrontHelper
{
	public static function getAppName()
    {
        return config('name', 'Sumba Awa');
    }

    public static function getEnvFolder()
    {
        $folder = null;
        if(!App::environment('local')) {
            $folder = 'public/';
        }
        return $folder;
    }

    /**
     * Obtenir la devise courante
     */
    public static function current_currency()
    {
        // Vérifier d'abord la config
        if (config('app.current_currency')) {
            return config('app.current_currency');
        }

        // Sinon, essayer de récupérer depuis le cookie
        if (request()->hasCookie('currency_code')) {
            $currency = Currency::where('code', request()->cookie('currency_code'))
                               ->where('is_active', true)
                               ->first();
            if ($currency) {
                config(['app.current_currency' => $currency]);
                return $currency;
            }
        }

        // Utilisateur connecté avec préférence
        if (auth()->check() && auth()->user()->preferred_currency_id) {
            $currency = Currency::find(auth()->user()->preferred_currency_id);
            if ($currency) {
                config(['app.current_currency' => $currency]);
                return $currency;
            }
        }

        // Devise par défaut
        $defaultCurrency = Currency::where('is_default', true)->first()
                          ?? Currency::where('code', 'XOF')->first();

        config(['app.current_currency' => $defaultCurrency]);
        return $defaultCurrency;
    }

    /**
     * Convertir un montant d'une devise vers une autre
     * Tous les montants en BD sont en XOF (devise de base)
     *
     * @param float $amount - Montant en XOF (devise de base)
     * @param string|null $fromCurrencyCode - Code de la devise source (si null, considéré comme XOF)
     * @param string|null $toCurrencyCode - Code de la devise cible (si null, utilise la devise courante)
     * @return float
     */
    public static function convert_currency($amount, $fromCurrencyCode = 'XOF', $toCurrencyCode = null)
    {
        // Si pas de devise cible spécifiée, utiliser la devise courante
        if (!$toCurrencyCode) {
            $currentCurrency = self::current_currency();
            $toCurrencyCode = $currentCurrency->code;
        }

        // Si les devises sont identiques, pas de conversion
        if ($fromCurrencyCode === $toCurrencyCode) {
            return $amount;
        }

        // Étape 1 : Convertir le montant en XOF (devise de base) si nécessaire
        $amountInXOF = $amount;

        if ($fromCurrencyCode !== 'XOF') {
            $fromCurrency = Currency::where('code', $fromCurrencyCode)
                                   ->where('is_active', true)
                                   ->first();

            if (!$fromCurrency) {
                return $amount; // Devise source non trouvée, retourner le montant original
            }

            // Convertir vers XOF : montant * taux
            $amountInXOF = $amount * $fromCurrency->exchange_rate;
        }

        // Étape 2 : Convertir de XOF vers la devise cible
        if ($toCurrencyCode === 'XOF') {
            return $amountInXOF;
        }

        $toCurrency = Currency::where('code', $toCurrencyCode)
                             ->where('is_active', true)
                             ->first();

        if (!$toCurrency) {
            return $amountInXOF; // Devise cible non trouvée, retourner le montant en XOF
        }

        // Convertir de XOF vers la devise cible : montant / taux
        return $amountInXOF / $toCurrency->exchange_rate;
    }

    /**
     * Formater un montant avec la devise
     *
     * @param float $amount - Montant en XOF (devise de base en BD)
     * @param string|null $currencyCode - Code de la devise pour l'affichage (si null, utilise la devise courante)
     * @return string
     */
    public static function format_currency($amount, $currencyCode = null)
    {
        $currentCurrency = self::current_currency();

        if (!$currencyCode) {
            $currencyCode = $currentCurrency->code;
        }

        $currency = Currency::where('code', $currencyCode)
                           ->where('is_active', true)
                           ->first();

        if (!$currency) {
            return number_format($amount, 0, ',', ' ') . ' XOF';
        }

        // Convertir le montant de XOF vers la devise cible
        $convertedAmount = self::convert_currency($amount, 'XOF', $currencyCode);

        // Format selon la devise (0 décimales pour XOF, 2 pour les autres)
        $decimals = ($currencyCode === 'XOF') ? 0 : 2;
        $formatted = number_format($convertedAmount, $decimals, ',', ' ');

        return $formatted . ' ' . $currency->symbol;
    }

    /**
     * Obtenir uniquement le montant converti sans formatage
     *
     * @param float $amount - Montant en XOF
     * @param string|null $currencyCode - Code de la devise cible
     * @return float
     */
    public static function get_converted_amount($amount, $currencyCode = null)
    {
        if (!$currencyCode) {
            $currentCurrency = self::current_currency();
            $currencyCode = $currentCurrency->code;
        }

        return self::convert_currency($amount, 'XOF', $currencyCode);
    }

    public static function allCategories()
    {

        $categories = Categorie::orderBy('label', 'asc')->get();
        return $categories;
    }

    public static function fiveCategories()
    {

        $categories = Categorie::orderBy('label', 'asc')->take(5)->get();
        return $categories;
    }

    public static function fiveCategoriesForFooter()
    {

        $categories = Categorie::orderBy('label', 'desc')->take(5)->get();
        return $categories;
    }

    public static function fourCategories()
    {

        $categories = Categorie::orderBy('label', 'desc')->take(4)->get();
        return $categories;
    }

    public static function fourProducts()
    {
        return Produit::orderBy('created_at', 'desc')->take(4)->get();
    }

    public static function fourProductsPopulars()
    {
        try {
            // Compter le nombre de fois où chaque produit apparaît dans les commandes
            $popularProducts = DB::table('commanders')
                ->join('commandes', 'commanders.commande_id', '=', 'commandes.id')
                ->join('produits', 'commanders.produit_id', '=', 'produits.id')
                ->select(
                    'commanders.produit_id',
                    DB::raw('SUM(commanders.quantity) as total_quantity'),
                    DB::raw('COUNT(DISTINCT commanders.commande_id) as order_count')
                )
                ->where('commandes.payment_status', 'paid')
                ->where('commandes.status', '!=', 'cancelled')
                ->groupBy('commanders.produit_id')
                ->orderByDesc('total_quantity')
                ->orderByDesc('order_count')
                ->orderByDesc(DB::raw('total_quantity * produits.price'))
                ->limit(4)
                ->pluck('produit_id')
                ->toArray();


            // Si on a trouvé des produits populaires, les récupérer avec leurs informations
            if (!empty($popularProducts)) {
                return Produit::whereIn('id', $popularProducts)
                    // ->where('is_active', true)
                    ->orderByRaw('FIELD(id, ' . implode(',', $popularProducts) . ')')
                    ->get();
            }

            // Fallback: produits les plus récents avec des ventes
            $fallbackProducts = DB::table('commanders')
                ->select('produit_id')
                ->join('produits', 'commanders.produit_id', '=', 'produits.id')
                // ->where('produits.is_active', true)
                ->groupBy('produit_id')
                ->havingRaw('SUM(quantity) > 0')
                ->orderByDesc('produits.created_at')
                ->take(4)
                ->get()
                ->pluck('produit_id')
                ->toArray();

            if (!empty($fallbackProducts)) {
                return Produit::whereIn('id', $fallbackProducts)
                    // ->where('is_active', true)
                    ->orderByRaw('FIELD(id, ' . implode(',', $fallbackProducts) . ')')
                    ->get();
            }

            // Dernier fallback: 4 derniers produits actifs
            return Produit::orderBy('created_at', 'desc')
                ->take(4)
                ->get();

        } catch (\Exception $e) {
            // En cas d'erreur, retourner les 4 derniers produits actifs
            Log::error('Erreur dans fourProductsPopulars: ' . $e->getMessage());

            return Produit::orderBy('created_at', 'desc')
                ->take(4)
                ->get();
        }
    }

    public static function randomProjectsInProgress()
    {
        if (Route::currentRouteName() != 'projects.progress') {
            $projects = Project::where('end_date', '>', Carbon::now())
            ->where('status', true)
            ->where('finalized', false)
            ->inRandomOrder()
            ->take(6)
            ->get();
        } else {
            $projects = Project::where('end_date', '>', Carbon::now())
            ->where('status', true)
            ->where('finalized', false)
            ->inRandomOrder()
            ->paginate(9);
        }

        return $projects;
    }

    public static function projectAuthor($id)
    {
        return User::where('id', $id)->first();
    }

    public static function projectValidator($id)
    {
        return User::where('id', $id)->first();
    }

    public static function countProjectf($id)
    {
        return Project::where('author_id', $id)->where('finalized', true)->count();
    }

    public static function countProject($id)
    {
        return Project::where('author_id', $id)->count();
    }

    public static function randomProjectsDonate()
    {

        $projects = Project::where('end_date', '>', Carbon::now())
            ->where('status', true)
            ->where('finalized', false)
            ->where('type_contribution_id', 1)
            ->inRandomOrder()
            ->take(6)
            ->get();
        return $projects;
    }

    public static function countProjectFinalized()
    {
        $projects = Project::where('finalized',true)->get();
        return count($projects) + 5;
    }

    public static function countProjectFinalizedD()
    {
        $projects = Project::where('finalized',true)->get();
        return count($projects) ;
    }

    public static function countProjectFinalizedC()
    {
        $projects = Project::where('finalized', true)
            ->where(function ($query) {
                $query->where('author_id', Auth::id())
                    ->orWhere('partner_id', Auth::id());
            })
            ->get();

        return $projects->count();
    }

    public static function getCountPA()
    {
        $projects = Project::where('partner_id', Auth::id())->get();

        return $projects->count();
    }

    public static function countProjectCreate()
    {
        $projects = Project::all();
        return count($projects) ;
    }

    public static function countProjectCreateC()
    {
        $projects = Project::where('author_id', Auth::id())->get();
        return count($projects) ;
    }

    public static function countUsers()
    {
        $users = User::all();
        return count($users) + 75;
    }

    public static function countUsersD()
    {
        $users = User::all();
        return count($users);
    }

    public static function countPartners()
    {
        $partners = Partner::all();
        return count($partners) + 15;
    }

    public static function daysUntilEnd(Project $project)
    {
        $endDate = Carbon::parse($project->end_date);
        $now = Carbon::now();

        // Calculate the difference in days
        $daysUntilEnd = $now->diffInDays($endDate, false);
        // dd($daysUntilEnd);

        // If the end date is in the past, return the negative of the days until end
        if ($now->greaterThan($endDate)) {
            return (int) -1 * $daysUntilEnd;
        }

        return (int) $daysUntilEnd;
    }

    public static function calculatePercentage(Project $project)
    {
        $percentage = ($project->current_amount / $project->goal_amount) * 100;
        return (int) $percentage ;
    }

    public static function allServicesForHeader()
    {

        $services = Departement::where('status', true)->get();
        return $services;
    }

    public static function allActualities()
    {
        if (Route::currentRouteName() != 'news')
        {
            $actualities = Actuality::orderBy('created_at', 'desc')->where('status', true)->paginate(3);
        }
        else
        {
            $actualities = Actuality::orderBy('created_at', 'desc')->where('status', true)->paginate(9);
        }
        return $actualities;
    }

    public static function allFormations()
    {
        if (Route::currentRouteName() != 'formations')
        {
            $formations = Formation::orderBy('created_at', 'desc')->where('status', true)->paginate(3);
        }
        else
        {
            $formations = Formation::orderBy('created_at', 'desc')->where('status', true)->paginate(9);
        }
        return $formations;
    }

    public static function allFaqs()
    {
        $faqs = Faq::orderBy('created_at', 'desc')->where('status', true)->where('project_id', null)->get();
        return $faqs;
    }

    public static function countFaqs(Project $project)
    {
        return Faq::where('project_id', $project->id)->where('status', true)->count();
    }

    public static function countRealizations(Project $project)
    {
        return Realization::where('project_id', $project->id)->where('status', true)->count();
    }

    public static function allProjectFaqs(Project $project)
    {
        return Faq::orderBy('created_at', 'desc')->where('project_id', $project->id)->where('status', true)->get();
    }

    public static function allProjectRealizations(Project $project)
    {
        return Realization::orderBy('created_at', 'desc')->where('project_id', $project->id)->where('status', true)->paginate(2);
    }

    public static function trueCommentas(Actuality $actuality)
    {
        return Comment::orderBy('created_at', 'desc')->where('status', true)->where('actuality_id', $actuality->id)->paginate(3);
    }

    public static function countTrueCommentas(Actuality $actuality)
    {
        return count(Comment::where('status', true)->where('actuality_id', $actuality->id)->get());
    }

    public static function trueCommentps(Project $project)
    {
        return Comment::orderBy('created_at', 'desc')->where('status', true)->where('project_id', $project->id)->paginate(3);
    }

    public static function countTrueCommentps(Project $project)
    {
        return count(Comment::where('status', true)->where('project_id', $project->id)->get());
    }

    public static function countCommentps(Project $project)
    {
        return count(Comment::where('project_id', $project->id)->get());
    }

    public static function similarProjects(Project $project)
    {
        $projects = Project::where('end_date', '>', Carbon::now())
            ->where('status', true)
            ->where('finalized', false)
            ->where('id', '!=', $project->id)
            ->where('type_contribution_id', $project->type_contribution_id)
            ->inRandomOrder()
            ->take(3)
            ->get();
        return $projects;
    }


    public static function allTestimonials()
    {
        $testimonials = Testimonial::where('status', true)
            ->inRandomOrder()
            ->take(4)
            ->get();
        return $testimonials;
    }

    public static function getCustomerEnterprise(User $user)
    {
        $entreprise = Enterprise::where('user_id', $user->id)->first();
        return $entreprise->name;
    }

    public static function allTeams()
    {
        $teams = User::orderBy('id', 'asc')
                            ->where('poste', '!=', null)
                            ->take(6)
                            ->get();
        return $teams;
    }

    public static function allPartners()
    {
        $partners = Partner::all();
        return $partners;
    }

    public static function allTypes()
    {
        return Type::all();
    }

    public static function allPays()
    {
        return Pays::orderBy('name', 'asc')->get();
    }

    public static function isProductInFavorites($productId)
    {
        if (!Auth::check()) {
            return false;
        }

        $userId = Auth::id();

        return Wishlist::where('user_id', $userId)
            ->where('produit_id', $productId)
            ->exists();
    }

    public static function getCountMoneys()
    {

        $money = Project::select(
            DB::raw('SUM(
                CASE
                    WHEN type_contribution_id = (SELECT id FROM type_contributions WHERE name = "Prévente" LIMIT 1)
                    THEN unit_price * current_amount
                    ELSE current_amount
                END
            ) as total_money')
        )->value('total_money');

        // $money = Project::sum('current_amount');
        return $money + 4000000;
    }

    public static function getCountMoneysD()
    {

        $money = Project::select(
            DB::raw('SUM(
                CASE
                    WHEN type_contribution_id = (SELECT id FROM type_contributions WHERE name = "Prévente" LIMIT 1)
                    THEN unit_price * current_amount
                    ELSE current_amount
                END
            ) as total_money')
        )->value('total_money');

        // $money = Project::sum('current_amount');
        return $money;
    }

    public static function getCountMoneysC()
    {

        $money = Project::where(function ($query) {
            $query->where('author_id', Auth::id())
                  ->orWhere('partner_id', Auth::id());
        })
        ->select(
            DB::raw('SUM(
                CASE
                    WHEN type_contribution_id = (SELECT id FROM type_contributions WHERE name = "Prévente" LIMIT 1)
                    THEN unit_price * current_amount
                    ELSE current_amount
                END
            ) as total_money')
        )->value('total_money');

        return $money;

    }

    public static function getSetting()
    {
        $setting = Setting::where('id', 1)->first();
        return $setting;
    }

    public static function getPhones()
    {
        $phones = Phone::where('enterprise_id', null)->get();
        return $phones;
    }

    public static function getPhone()
    {
        $phone = Phone::where('id', 1)->first();
        return $phone->name;
    }

    public static function getEmail()
    {
        $email = Email::where('id', 1)->first();
        return $email->name;
    }

    public static function getEmails()
    {
        $emails = Email::where('enterprise_id', null)->get();
        return $emails;
    }

    public static function getFormattedPrice($price)
    {
        $price = floatval($price);
        return number_format($price, 0, '.', ' ');
    }

    public static function getPublicKkiapayKey()
    {
        return '82880cf0652e11efbf02478c5adba4b8';
        // return 'c818da4123f2eca11d4741188bd67f76f6b4401a';
    }

    public static function getPublicFedapayKey()
    {
        return 'pk_sandbox_3V8x4guB42pxHXchiYqzCYxP';
    }

    public static function getPrivateKkiapayKey()
    {
        return 'tpk_82880cf2652e11efbf02478c5adba4b8';
        // return 'pk_b060007987131b58f6cf9cb328342ff0716971e411a16d5299557c98b7361531';
    }

    public static function getSecretKkiapayKey()
    {
        return 'tsk_82880cf3652e11efbf02478c5adba4b8';
        // return 'sk_47d7b2e116186e4dd008e02a4c59497d739c6c277e104b1893be28b274681849';
    }

    public static function getAllAdmins()
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin')
                ->orWhere('name', 'dev');
        })->get();

        return $admins;
    }

    public static function getUserProjectContributes(Contribute $contribute)
    {
        return Contribute::orderBy('created_at', 'desc')->where('user_id', $contribute->user_id)->where('project_id', $contribute->project_id)->get();
    }

    public static function getUserProjectPercent(Contribute $contribute)
    {
        $contributes =  Contribute::orderBy('created_at', 'desc')->where('user_id', $contribute->user_id)->where('project_id', $contribute->project_id)->get();
        // Calcul du capital total du projet
        $total_capital = $contribute->project->current_amount;
        // Calcul du nombre total d'actions achetées par l'utilisateur
        $total_shares = $contributes->sum(function($contribut) {
            return $contribut->quantity;
        });


        // Calcul du pourcentage des parts de l'utilisateur
        $percentage = ($total_shares * $contribute->project->unit_price) / $total_capital * 100;
        return $percentage;
    }

    public static function hasCompletedProfile(User $user)
    {
        $requiredFields = [
            'name',
            'email',
            'phone',
            'address',
            'type',
            'identity_card',
            'domain_id',
            'country_id',
        ];

        if ($user->type !== 'Porteur de projet') {
            $requiredFields = array_merge($requiredFields, [
                'agent',
                'agent_email',
                'agent_phone',
                'ifu',
                'rccm',
            ]);
        }

        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                return false;
            }
        }

        return true;
    }


    public static function isInUEMOA(User $user)
    {
        $country = $user->country;

        if ($country && $country->uemoa) {
            return true;
        }

        return false;
    }

    public static function totalCancelledContributions($id)
    {
        $user = User::find($id);
        if (!$user) {
            return 0; // Retourne 0 si l'utilisateur n'est pas trouvé
        }

        // Somme des contributions pour les projets annulés
        $totalContributions = Contribute::where('user_id', $user->id)
            ->whereHas('project', function ($query) {
                $query->where('canceled', true);
            })
            ->sum('amount');

        // Somme des remboursements liés à ces contributions
        $totalRefunds = Contribute::where('user_id', $user->id)
            ->whereHas('project', function ($query) {
                $query->where('canceled', true);
            })
            ->withSum('refunds', 'amount')
            ->get()
            ->sum('refunds_sum_amount');

        // Somme des contributions avec transaction_id commençant par "Crédit LeCapitaL"
        // $totalCapitalCredits = Contribute::where('user_id', $user->id)
        //     ->where('transaction_id', 'LIKE', 'Crédit LeCapitaL%')
        //     ->sum('amount');

        // Retourner la différence (contributions - remboursements - contributions en crédit LeCapitaL)
        return $totalContributions - $totalRefunds;
    }


    public static function remainder(Contribute $contribute)
    {
        $totalPreviousRefunds = Refund::where('contribute_id', $contribute->id)->sum('amount');
        $remainingAmount = $contribute->amount - $totalPreviousRefunds;
        return $remainingAmount;
    }

    public static function distributeRefundsForCancelledProjects($userId, $amount, $title)
    {
        // Vérifier si l'utilisateur existe et que amount est positif
        $user = User::find($userId);
        if (!$user || $amount <= 0) {
            return 0; // Retourne 0 si pas d'utilisateur ou montant invalide
        }

        // Récupérer toutes les contributions de l'utilisateur pour les projets annulés
        $contributions = Contribute::where('user_id', $userId)
            ->whereHas('project', function ($query) {
                $query->where('canceled', true);
            })
            ->with('refunds') // Charger les remboursements existants
            ->get();

        $remainingAmount = $amount; // Montant restant à répartir

        // Parcourir les contributions
        foreach ($contributions as $contribution) {
            if ($remainingAmount <= 0) {
                break; // Sortir si le montant est épuisé
            }

            // Calculer le reste disponible pour cette contribution
            $remainder = self::remainder($contribution);

            if ($remainder > 0) {
                // Déterminer le montant à rembourser : le minimum entre le reste et ce qu'il reste de $amount
                $refundAmount = min($remainder, $remainingAmount);

                // Créer un nouveau remboursement
                $refund = Refund::create([
                    'contribute_id' => $contribution->id,
                    'amount' => $refundAmount,
                    'user_id' => $userId,
                    'reference' => Str::random(7),
                    'status' => 'Payé',
                    'transaction_id' => 'F.' . $title . Str::random(3),
                ]);

                // Mettre à jour le montant restant
                $remainingAmount -= $refundAmount;

                // Ajouter à la liste des remboursements créés (facultatif, pour suivi)
            }
        }

        // Retourner le montant restant non utilisé (devrait être 0 si tout est épuisé)
        return $remainingAmount;
    }

    public static function getUserProjectContributesForProject($id)
    {
        $contributes = Contribute::where('project_id', $id)->where('user_id', Auth::id())->get();
        return $contributes;
    }

    public static function encashmentPaid($id)
    {
        return Encashment::where('project_id', $id)->where('status', true)->sum('amount');
    }


}

?>
