
@extends('layouts.app')

@section('title', 'Mes Notifications')
@section('page-title', 'Mes Notifications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-bell text-primary"></i> Mes Notifications
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger">{{ $unreadCount }} non lue(s)</span>
                                @endif
                            </h4>
                        </div>
                        
                        <div class="btn-group">
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-check-double"></i> Tout marquer
                                    </button>
                                </form>
                            @endif
                            
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteReadNotifications()">
                                <i class="fas fa-trash"></i> Supprimer lues
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    @forelse($notifications as $notification)
                        <div class="notification-item {{ $notification->read_at ? '' : 'unread' }} p-3 border-bottom">
                            <div class="d-flex align-items-start gap-3">
                                <div class="notification-icon {{ $notification->data['type'] ?? 'info' }}">
                                    <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0 fw-semibold">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                            @if(!$notification->read_at)
                                                <span class="badge bg-primary ms-2">Nouveau</span>
                                            @endif
                                        </h5>
                                        
                                        <div class="btn-group btn-group-sm">
                                            @if(!$notification->read_at)
                                                <button class="btn btn-outline-primary" 
                                                        onclick="markAsRead('{{ $notification->id }}')"
                                                        title="Marquer comme lu">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            
                                            @if(isset($notification->data['url']))
                                                <a href="{{ $notification->data['url'] }}" 
                                                   class="btn btn-outline-secondary"
                                                   title="Voir">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                            
                                            <button class="btn btn-outline-danger" 
                                                    onclick="deleteNotificationInPage('{{ $notification->id }}')"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <p class="mb-2 text-muted">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i> 
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune notification</h5>
                            <p class="text-muted">Vous êtes à jour !</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($notifications->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) location.reload();
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        }
    }

    async function deleteNotificationInPage(notificationId) {
        if (!confirm('Supprimer cette notification ?')) return;
        
        try {
            const response = await fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            
            if (response.ok) location.reload();
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        }
    }

    async function deleteReadNotifications() {
        if (!confirm('Supprimer toutes les notifications lues ?')) return;
        
        try {
            const response = await fetch('{{ route("notifications.delete-read") }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            
            if (response.ok) location.reload();
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        }
    }
</script>
@endpush
@endsection