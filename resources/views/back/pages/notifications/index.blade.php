@extends('back.layouts.master')

@section('title', 'Mes Notifications')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-notification-line me-2"></i>Mes Notifications
                    </h4>
                    @if($notifications->where('is_read', false)->count() > 0)
                    <button type="button" id="markAllAsRead" class="btn btn-light btn-sm">
                        <i class="ri-check-double-line me-1"></i> Tout marquer comme lu
                    </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($notifications->isEmpty())
                        <div class="text-center py-5">
                            <i class="ri-notification-off-line display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune notification</h5>
                            <p class="text-muted">Vous n'avez pas encore de notifications.</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action
                                @if(!$notification->is_read) list-group-item-light @endif"
                                data-id="{{ $notification->id }}">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-start">
                                            @if(!$notification->is_read)
                                            <span class="badge bg-danger me-2 mt-1">Nouveau</span>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                                <p class="mb-1 text-muted">{!! nl2br(e($notification->message)) !!}</p>

                                                @if($notification->data && isset($notification->data['url']))
                                                <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="ri-external-link-line me-1"></i> Voir les détails
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">
                                            {{ $notification->created_at->format('d/m/Y H:i') }}
                                        </small>
                                        <div class="btn-group btn-group-sm mt-1">
                                            @if(!$notification->is_read)
                                            <button type="button" class="btn btn-outline-success mark-as-read-btn"
                                                    data-id="{{ $notification->id }}"
                                                    title="Marquer comme lu">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-danger delete-notification-btn"
                                                    data-id="{{ $notification->id }}"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $notifications->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Charger jQuery 3.6.0 si nécessaire
    window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"><\/script>');
</script>
<script>
    $(document).ready(function() {
        // Marquer une notification comme lue
        $('.mark-as-read-btn').click(function() {
            const notificationId = $(this).data('id');
            const $item = $(this).closest('.list-group-item');

            $.ajax({
                url: `/notifications/${notificationId}/read`,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $item.removeClass('list-group-item-light');
                        $item.find('.badge.bg-danger').remove();
                        $item.find('.mark-as-read-btn').remove();

                        // Mettre à jour le compteur dans le header
                        updateNotificationCount(response.unread_count);
                    }
                }
            });
        });

        // Marquer toutes les notifications comme lues
        $('#markAllAsRead').click(function() {
            $.ajax({
                url: '/notifications/read-all',
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('.list-group-item').removeClass('list-group-item-light');
                        $('.badge.bg-danger').remove();
                        $('.mark-as-read-btn').remove();
                        $('#markAllAsRead').remove();

                        // Mettre à jour le compteur dans le header
                        updateNotificationCount(response.unread_count);
                    }
                }
            });
        });

        // Supprimer une notification
        $('.delete-notification-btn').click(function() {
            const notificationId = $(this).data('id');
            const $item = $(this).closest('.list-group-item');

            if (confirm('Supprimer cette notification ?')) {
                $.ajax({
                    url: `/notifications/${notificationId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $item.remove();

                            // Vérifier si la liste est vide
                            if ($('.list-group-item').length === 0) {
                                location.reload();
                            }
                        }
                    }
                });
            }
        });

        function updateNotificationCount(count) {
            // Mettre à jour le compteur dans le header si existant
            const $badge = $('#notification-badge');
            if ($badge.length) {
                if (count > 0) {
                    $badge.text(count).show();
                } else {
                    $badge.hide();
                }
            }
        }
    });
</script>
@endsection
