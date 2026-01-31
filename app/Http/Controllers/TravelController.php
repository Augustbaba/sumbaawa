<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    /**
     * Liste des inscriptions Nihao Travel
     */
    public function index(Request $request)
    {
        $query = Travel::latest();

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('canton_edition')) {
            $query->where('canton_edition', $request->canton_edition);
        }

        $travels = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Travel::count(),
            'pending' => Travel::where('status', 'pending')->count(),
            'confirmed' => Travel::where('status', 'confirmed')->count(),
            'processing' => Travel::where('status', 'processing')->count(),
            'completed' => Travel::where('status', 'completed')->count(),
            'cancelled' => Travel::where('status', 'cancelled')->count(),
            'paid' => Travel::where('payment_status', 'paid')->count(),
            'pending_payment' => Travel::where('payment_status', 'pending')->count(),
        ];

        // Options d'édition pour le filtre
        $editions = Travel::select('canton_edition')
            ->distinct()
            ->orderBy('canton_edition')
            ->pluck('canton_edition');

        return view('back.pages.nihao-travel.index', compact('travels', 'stats', 'editions'));
    }

    /**
     * Afficher les détails d'une inscription
     */
    public function show($id)
    {
        $travel = Travel::findOrFail($id);

        // Logique pour déterminer la prochaine étape
        // $nextSteps = $this->getNextSteps($travel);

        return view('back.pages.nihao-travel.show', compact('travel'));
    }

    // /**
    //  * Mettre à jour le statut d'une inscription
    //  */
    // public function updateStatus(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
    //         'notes' => 'nullable|string|max:500'
    //     ]);

    //     $travel = Travel::findOrFail($id);
    //     $oldStatus = $travel->status;
    //     $newStatus = $request->status;

    //     $travel->update([
    //         'status' => $newStatus,
    //         'admin_notes' => $request->notes ?: $travel->admin_notes
    //     ]);

    //     // Envoyer un email de notification si le statut a changé
    //     if ($oldStatus !== $newStatus) {
    //         try {
    //             Mail::to($travel->email)->send(new NihaoTravelStatusUpdate($travel, $oldStatus, $newStatus));
    //         } catch (\Exception $e) {
    //             \Log::error('Erreur envoi email mise à jour statut: ' . $e->getMessage());
    //         }
    //     }

    //     return back()->with('success', 'Statut mis à jour avec succès');
    // }

    // /**
    //  * Mettre à jour le statut de paiement
    //  */
    // public function updatePaymentStatus(Request $request, $id)
    // {
    //     $request->validate([
    //         'payment_status' => 'required|in:pending,paid,failed,refunded'
    //     ]);

    //     $travel = Travel::findOrFail($id);
    //     $travel->update(['payment_status' => $request->payment_status]);

    //     return back()->with('success', 'Statut de paiement mis à jour avec succès');
    // }

    // /**
    //  * Ajouter une note administrative
    //  */
    // public function addNote(Request $request, $id)
    // {
    //     $request->validate([
    //         'admin_notes' => 'required|string|max:1000'
    //     ]);

    //     $travel = Travel::findOrFail($id);
    //     $travel->update(['admin_notes' => $request->admin_notes]);

    //     return back()->with('success', 'Note ajoutée avec succès');
    // }

    /**
     * Télécharger la liste des inscriptions en CSV
     */
    public function export(Request $request)
    {
        $query = Travel::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $travels = $query->get();

        $filename = "nihao-travel-inscriptions-" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($travels) {
            $file = fopen('php://output', 'w');

            // En-tête CSV en français
            fputcsv($file, [
                'Code', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Entreprise',
                'Édition Canton', 'Montant XOF', 'Montant USD', 'Statut',
                'Statut Paiement', 'Date Inscription', 'Dernière Mise à Jour'
            ], ';');

            foreach ($travels as $travel) {
                fputcsv($file, [
                    $travel->code,
                    $travel->last_name,
                    $travel->first_name,
                    $travel->email,
                    $travel->phone,
                    $travel->company ?? 'N/A',
                    $travel->canton_edition,
                    number_format($travel->amount_xof, 2, ',', ' '),
                    number_format($travel->amount_usd, 2, ',', ' '),
                    $this->getStatusLabel($travel->status),
                    $this->getPaymentStatusLabel($travel->payment_status),
                    $travel->created_at->format('d/m/Y H:i'),
                    $travel->updated_at->format('d/m/Y H:i')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Supprimer une inscription (admin seulement)
     */
    public function destroy($id)
    {
        // $travel = Travel::findOrFail($id);
        // $travel->delete();

        // return redirect()->route('admin.nihao-travel.index')
        //     ->with('success', 'Inscription supprimée avec succès');
    }

    /**
     * Obtenir les prochaines étapes recommandées
     */
    private function getNextSteps($travel)
    {
        $steps = [];

        switch ($travel->status) {
            case 'pending':
                $steps = [
                    ['action' => 'Contacter le client', 'icon' => 'phone', 'priority' => 'high'],
                    ['action' => 'Demander les documents', 'icon' => 'file-text', 'priority' => 'medium'],
                    ['action' => 'Planifier l\'entretien', 'icon' => 'calendar', 'priority' => 'medium'],
                ];
                break;

            case 'confirmed':
                $steps = [
                    ['action' => 'Collecter les documents', 'icon' => 'folder', 'priority' => 'high'],
                    ['action' => 'Démarrer la demande de visa', 'icon' => 'passport', 'priority' => 'high'],
                    ['action' => 'Rechercher hébergement', 'icon' => 'hotel', 'priority' => 'medium'],
                ];
                break;

            case 'processing':
                $steps = [
                    ['action' => 'Suivi demande visa', 'icon' => 'clock', 'priority' => 'high'],
                    ['action' => 'Réservation hébergement', 'icon' => 'check-circle', 'priority' => 'high'],
                    ['action' => 'Organiser transport', 'icon' => 'plane', 'priority' => 'medium'],
                ];
                break;

            case 'completed':
                $steps = [
                    ['action' => 'Feedback client', 'icon' => 'message-square', 'priority' => 'low'],
                    ['action' => 'Archiver dossier', 'icon' => 'archive', 'priority' => 'low'],
                ];
                break;

            default:
                $steps = [
                    ['action' => 'Analyser la situation', 'icon' => 'search', 'priority' => 'medium'],
                ];
        }

        return $steps;
    }

    /**
     * Libellé des statuts
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'processing' => 'En traitement',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Libellé des statuts de paiement
     */
    private function getPaymentStatusLabel($status)
    {
        $labels = [
            'pending' => 'En attente',
            'paid' => 'Payé',
            'failed' => 'Échoué',
            'refunded' => 'Remboursé'
        ];

        return $labels[$status] ?? $status;
    }
}
