@extends('back.layouts.master')
@section('title', 'Solde PayPal')

@section('styles')
<style>
    :root {
        --paypal-blue: #003087;
        --paypal-light: #009cde;
        --accent: #b78d65;
        --accent-dark: #9a7150;
        --success: #22c55e;
        --danger: #ef4444;
        --text-muted: #6b7280;
        --bg-light: #f9fafb;
    }

    .balance-page { padding: 2rem 0; }

    /* ── CARTES STATS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 2rem 1.75rem;        /* était 1.5rem */
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    border-top: 4px solid var(--accent);
    display: flex;
    flex-direction: column;
    gap: 0.6rem;                  /* était 0.5rem */
        min-height: 140px;            /* hauteur minimale garantie */
    }


    .stat-card.balance-card { border-top-color: var(--paypal-blue); }
    .stat-card.out-card     { border-top-color: var(--danger); }
    .stat-card.in-card      { border-top-color: var(--success); }

    .stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        font-weight: 600;
    }


    .stat-value {
        font-size: 1rem;           /* était 1.6rem */
        font-weight: 800;
        color: #1a1a1a;
        line-height: 1;
    }

    .stat-card.balance-card .stat-value { color: var(--paypal-blue); }
    .stat-card.out-card .stat-value     { color: var(--danger); }
    .stat-card.in-card .stat-value      { color: var(--success); }

    .stat-icon {
        font-size: 2.2rem;            /* était 1.8rem */
        opacity: 0.12;
        align-self: flex-end;
        margin-top: -3rem;            /* était -2.5rem */
    }

    /* ── DÉTAIL ENTRÉES ── */
    .breakdown-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        margin-bottom: 2rem;
    }

    .breakdown-card h5 {
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .breakdown-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e5e7eb;
        font-size: 0.95rem;
    }

    .breakdown-row:last-child { border-bottom: none; }

    .breakdown-row .label { color: var(--text-muted); }
    .breakdown-row .value { font-weight: 700; color: #1a1a1a; }

    /* ── FORMULAIRE RETRAIT ── */
    .withdrawal-form-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        margin-bottom: 2rem;
    }

    .withdrawal-form-card h5 {
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .form-row-inline {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .form-row-inline .form-group { flex: 1; min-width: 160px; }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(183,141,101,0.15);
    }

    .btn-submit {
        padding: 0.7rem 1.5rem;
        background: var(--accent);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s, transform 0.1s;
    }

    .btn-submit:hover { background: var(--accent-dark); transform: translateY(-1px); }

    /* ── TABLE RETRAITS ── */
    .table-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .table-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        font-weight: 700;
        font-size: 1rem;
        color: #1a1a1a;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-responsive { overflow-x: auto; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    thead th {
        background: var(--bg-light);
        padding: 0.85rem 1.25rem;
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafafa; }

    tbody td {
        padding: 0.9rem 1.25rem;
        color: #374151;
        vertical-align: middle;
    }

    .amount-cell {
        font-weight: 700;
        color: var(--danger);
    }

    .note-cell {
        color: var(--text-muted);
        font-size: 0.85rem;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--accent-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .btn-delete {
        background: none;
        border: 1px solid #fecaca;
        color: var(--danger);
        border-radius: 6px;
        padding: 0.3rem 0.7rem;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-delete:hover { background: #fef2f2; }

    /* ── ALERTS ── */
    .alert {
        padding: 0.85rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .alert-danger  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }

    .empty-state i { font-size: 3rem; opacity: 0.3; margin-bottom: 0.5rem; }

    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .form-row-inline { flex-direction: column; }
        .btn-submit { width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="balance-page">
    <div class="container">

        {{-- Titre --}}
        <div style="margin-bottom:1.5rem;">
            <h2 style="font-weight:800; margin:0;">
                <i class="bi bi-paypal" style="color:#003087;"></i> Solde PayPal
            </h2>
            <p style="color:var(--text-muted); margin:4px 0 0; font-size:0.9rem;">
                Vue consolidée des entrées et retraits PayPal
            </p>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-checkbox-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-error-warning"></i> {{ session('error') }}
            </div>
        @endif

        {{-- STATS CARDS --}}
        <div class="stats-grid">
            <div class="stat-card balance-card">
                <div class="stat-label">Solde actuel</div>
                <div class="stat-value">
                    {{ number_format($balance, 0, ',', ' ') }} <small style="font-size:0.9rem;">XOF</small>
                </div>
                <div class="stat-icon"><i class="bi bi-paypal"></i></div>
            </div>
            <div class="stat-card in-card">
                <div class="stat-label">Total entrées</div>
                <div class="stat-value">
                    {{ number_format($totalIn, 0, ',', ' ') }} <small style="font-size:0.9rem;">XOF</small>
                </div>
                <div class="stat-icon"><i class="bi bi-arrow-down-circle"></i></div>
            </div>
            <div class="stat-card out-card">
                <div class="stat-label">Total retraits</div>
                <div class="stat-value">
                    {{ number_format($totalWithdrawals, 0, ',', ' ') }} <small style="font-size:0.9rem;">XOF</small>
                </div>
                <div class="stat-icon"><i class="bi bi-arrow-up-circle"></i></div>
            </div>
        </div>

        {{-- DÉTAIL ENTRÉES --}}
        <div class="breakdown-card">
            <h5><i class="ri-bar-chart-line"></i> Détail des entrées</h5>
            <div class="breakdown-row">
                <span class="label"><i class="ri-shopping-bag-line"></i> Commandes (PayPal)</span>
                <span class="value">{{ number_format($commandesTotal, 0, ',', ' ') }} XOF</span>
            </div>
            <div class="breakdown-row">
                <span class="label"><i class="ri-truck-line"></i> Frais de livraison (PayPal)</span>
                <span class="value">{{ number_format($shippingTotal, 0, ',', ' ') }} XOF</span>
            </div>
            <div class="breakdown-row">
                <span class="label"><i class="ri-plane-line"></i> Nihao Travel (PayPal)</span>
                <span class="value">{{ number_format($travelTotal, 0, ',', ' ') }} XOF</span>
            </div>
            <div class="breakdown-row" style="font-weight:700; font-size:1rem;">
                <span>Total</span>
                <span style="color:var(--success);">{{ number_format($totalIn, 0, ',', ' ') }} XOF</span>
            </div>
        </div>

        {{-- FORMULAIRE RETRAIT --}}
        <div class="withdrawal-form-card">
            <h5><i class="ri-add-circle-line"></i> Enregistrer un retrait</h5>
            <form action="{{ route('admin.paypal-balance.store') }}" method="POST">
                @csrf
                <div class="form-row-inline">
                    <div class="form-group">
                        <label>Montant (XOF) *</label>
                        <input type="number" name="amount" min="1" step="0.01"
                               placeholder="ex: 500000"
                               value="{{ old('amount') }}" required>
                        @error('amount')
                            <span style="color:var(--danger); font-size:0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label>Note (optionnel)</label>
                        <input type="text" name="note"
                               placeholder="ex: Retrait mensuel janvier 2026"
                               value="{{ old('note') }}">
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="ri-save-line"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE RETRAITS --}}
        <div class="table-card">
            <div class="table-card-header">
                <span><i class="ri-history-line"></i> Historique des retraits</span>
                <span style="font-size:0.85rem; color:var(--text-muted); font-weight:400;">
                    {{ $withdrawals->total() }} retrait(s)
                </span>
            </div>

            @if($withdrawals->isEmpty())
                <div class="empty-state">
                    <div><i class="ri-inbox-line"></i></div>
                    <p>Aucun retrait enregistré</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Note</th>
                                <th>Enregistré par</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $index => $withdrawal)
                            <tr>
                                <td style="color:var(--text-muted);">
                                    {{ $withdrawals->firstItem() + $index }}
                                </td>
                                <td>
                                    {{ $withdrawal->created_at->format('d/m/Y') }}<br>
                                    <small style="color:var(--text-muted);">
                                        {{ $withdrawal->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td class="amount-cell">
                                    - {{ number_format($withdrawal->amount, 0, ',', ' ') }} XOF
                                </td>
                                <td class="note-cell" title="{{ $withdrawal->note }}">
                                    {{ $withdrawal->note ?? '—' }}
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($withdrawal->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <span>{{ $withdrawal->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                {{-- <td>
                                    <form action="{{ route('admin.paypal-balance.destroy', $withdrawal->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Supprimer ce retrait ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <i class="ri-delete-bin-line"></i> Supprimer
                                        </button>
                                    </form>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($withdrawals->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #f3f4f6;">
                    {{ $withdrawals->links('pagination::bootstrap-5') }}
                </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection
