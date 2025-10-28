@extends($layout ?? 'layouts.app')
@section('title', 'Messagerie')

@section('content')
<style>
  .messages-container {
    max-width: 1200px;
    margin: 0 auto;
  }

  /* --- Header --- */
  .header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
  }

  .header-section h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
  }

  /* --- Barre d'actions --- */
  .actions-bar {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    align-items: center;
  }

  .btn-custom {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
  }

  .btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
  }

  .btn-secondary-custom {
    background: #e2e8f0;
    color: #334155;
  }

  .btn-secondary-custom:hover {
    background: #cbd5e1;
  }

  /* --- Cartes de message --- */
  .thread-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 2px solid #e2e8f0;
    transition: all 0.3s;
    cursor: pointer;
    overflow: hidden;
  }

  .thread-card:hover {
    border-color: #667eea;
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
  }

  .thread-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
  }

  .avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 800;
    font-size: 1.3rem;
    flex-shrink: 0;
  }

  .contact-info {
    flex: 1;
    min-width: 0;
  }

  .contact-name {
    font-weight: 700;
    font-size: 1.1rem;
    color: #1e293b;
    margin-bottom: 0.25rem;
  }

  .contact-role {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  .role-admin { background: #fee2e2; color: #991b1b; }
  .role-professeur { background: #ede9fe; color: #6b21a8; }
  .role-stagiaire { background: #d1fae5; color: #065f46; }

  .last-message {
    color: #64748b;
    font-size: 0.9rem;
    margin-top: 0.5rem;
  }

  .message-preview {
    white-space: normal;
    overflow: hidden;
    text-overflow: ellipsis;
    word-wrap: break-word;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* max 2 lignes */
    -webkit-box-orient: vertical;
    line-height: 1.4;
    max-height: 2.8em;
  }

  .thread-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.5rem;
    flex-shrink: 0;
  }

  .timestamp {
    color: #94a3b8;
    font-size: 0.85rem;
  }

  .unread-badge {
    background: #ef4444;
    color: white;
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 0.8rem;
    font-weight: 700;
    white-space: nowrap;
  }

  /* --- État vide --- */
  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
  }

  .empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
  }

  .empty-state h3 {
    color: #475569;
    margin-bottom: 0.5rem;
  }

  .empty-state p {
    color: #94a3b8;
  }

  @media (max-width: 768px) {
    .thread-header { flex-direction: column; align-items: flex-start; }
    .thread-meta { flex-direction: row; width: 100%; justify-content: space-between; }
  }
</style>

<div class="messages-container">
  <!-- Header -->
  <div class="header-section">
    <h1><i class="fas fa-envelope me-2"></i> Messagerie</h1>
    <p class="mb-0">Gérez vos conversations et communiquez facilement</p>
  </div>
  
  <!-- Actions Bar -->
  <div class="actions-bar">
    <a href="{{ route('messages.create') }}" class="btn-custom btn-primary-custom">
      <i class="fas fa-plus-circle me-2"></i> Nouveau Message
    </a>
    
    @if(auth()->user()->role === 'administrateur' || auth()->user()->role === 'professeur')
      <a href="{{ route('messages.send-group.form') }}" class="btn-custom btn-secondary-custom">
        <i class="fas fa-users me-2"></i> Message Groupé
      </a>
    @endif
    
    {{-- 🔒 Bouton "Tout Supprimer" uniquement pour les administrateurs --}}
    @if(auth()->user()->role === 'administrateur' && $threads->count() > 0)
      <form action="{{ route('messages.reset') }}" method="POST" style="margin-left: auto;"
            onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer TOUTES vos conversations ?\n\nCette action est irréversible.')">
        @csrf
        <button type="submit" class="btn-custom" style="background:#ef4444; color:white;">
          <i class="fas fa-trash-alt me-2"></i> Tout Supprimer
        </button>
      </form>
    @endif
  </div>
  
  <!-- Threads List -->
  @if($threads->count() > 0)
    <div class="threads-list">
      @foreach($threads as $thread)
        @php
          $contact = $thread['contact'];
          $lastMsg = $thread['last_message'];
          $unreadCount = $thread['unread_count'];
        @endphp
        
        <a href="{{ route('messages.conversation', $contact->id) }}" style="text-decoration: none; color: inherit;">
          <div class="thread-card">
            <div class="thread-header">
              <div class="avatar">
                {{ strtoupper(substr($contact->name, 0, 1)) }}
              </div>
              
              <div class="contact-info">
                <div class="contact-name">
                  {{ $contact->name }}
                  
                  @if($contact->role === 'administrateur')
                    <span class="contact-role role-admin">Admin</span>
                  @elseif($contact->role === 'professeur')
                    <span class="contact-role role-professeur">Prof</span>
                  @elseif($contact->role === 'stagiaire')
                    <span class="contact-role role-stagiaire">Stagiaire</span>
                  @endif
                </div>
                
                <div class="last-message">
                  <div class="message-preview">
                    @if($lastMsg->sender_id === auth()->id())
                      <i class="fas fa-reply me-1"></i> <strong>Vous:</strong>
                    @endif
                    {{ $lastMsg->message }}
                  </div>
                </div>
              </div>
              
              <div class="thread-meta">
                <div class="timestamp">
                  <i class="far fa-clock me-1"></i>
                  {{ \Carbon\Carbon::parse($lastMsg->created_at)->diffForHumans() }}
                </div>
                
                @if($unreadCount > 0)
                  <div class="unread-badge">
                    {{ $unreadCount }} nouveau{{ $unreadCount > 1 ? 'x' : '' }}
                  </div>
                @endif
              </div>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  @else
    <div class="empty-state">
      <i class="fas fa-inbox"></i>
      <h3>Aucune conversation</h3>
      <p>Commencez une nouvelle conversation en cliquant sur "Nouveau Message"</p>
      <a href="{{ route('messages.create') }}" class="btn-custom btn-primary-custom mt-3">
        <i class="fas fa-plus-circle me-2"></i> Créer une Conversation
      </a>
    </div>
  @endif
</div>

@push('scripts')
<script>
  // Animation hover
  document.querySelectorAll('.thread-card').forEach(card => {
    card.addEventListener('mouseenter', () => card.style.transform = 'translateX(5px)');
    card.addEventListener('mouseleave', () => card.style.transform = 'translateX(0)');
  });

  // Rafraîchissement automatique du compteur de messages non lus
  setInterval(() => {
    fetch('{{ route("messages.unread-count") }}')
      .then(response => response.json())
      .then(data => {
        document.querySelectorAll('.badge-notification').forEach(badge => {
          if (data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline-block';
          } else {
            badge.style.display = 'none';
          }
        });
      })
      .catch(error => console.error('Erreur:', error));
  }, 30000);
</script>
@endpush
@endsection