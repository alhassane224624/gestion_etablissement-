@extends($layout ?? 'layouts.app')
@section('title', 'Nouveau Message')

@section('content')
<style>
  .message-create-container { max-width: 800px; margin: 0 auto; }
  
  .header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
  }
  
  .form-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
  }
  
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  .form-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    display: block;
  }
  
  .form-control-custom {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s;
  }
  
  .form-control-custom:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }
  
  select.form-control-custom {
    cursor: pointer;
  }
  
  textarea.form-control-custom {
    resize: vertical;
    min-height: 150px;
  }
  
  .btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
  }
  
  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
  }
  
  .btn-cancel {
    background: #e2e8f0;
    color: #334155;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    width: 100%;
    text-decoration: none;
    display: inline-block;
    text-align: center;
  }
  
  .btn-cancel:hover {
    background: #cbd5e1;
  }
  
  .actions-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 2rem;
  }
  
  .char-counter {
    text-align: right;
    font-size: 0.85rem;
    color: #94a3b8;
    margin-top: 0.25rem;
  }
  
  .user-option {
    padding: 0.5rem;
  }
  
  .role-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-left: 0.5rem;
  }
  
  .role-admin { background: #fee2e2; color: #991b1b; }
  .role-professeur { background: #ede9fe; color: #6b21a8; }
  .role-stagiaire { background: #d1fae5; color: #065f46; }
</style>

<div class="message-create-container">
  <!-- Header -->
  <div class="header-card">
    <h1><i class="fas fa-paper-plane me-2"></i> Nouveau Message</h1>
    <p class="mb-0">Envoyer un message à un utilisateur</p>
  </div>
  
  <!-- Form -->
  <div class="form-card">
    <form action="{{ route('messages.send.by-id') }}" method="POST" id="messageForm">
      @csrf
      
      <!-- Destinataire -->
      <div class="form-group">
        <label for="receiver_id" class="form-label">
          <i class="fas fa-user me-1"></i> Destinataire <span style="color: #ef4444;">*</span>
        </label>
        <select name="receiver_id" id="receiver_id" class="form-control-custom @error('receiver_id') is-invalid @enderror" required>
          <option value="">-- Sélectionner un destinataire --</option>
          @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
              {{ $user->name }}
              @if($user->role === 'administrateur')
                <span class="role-badge role-admin">ADMIN</span>
              @elseif($user->role === 'professeur')
                <span class="role-badge role-professeur">PROF</span>
              @elseif($user->role === 'stagiaire')
                <span class="role-badge role-stagiaire">STAGIAIRE</span>
              @endif
            </option>
          @endforeach
        </select>
        @error('receiver_id')
          <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
      </div>
      
      <!-- Message -->
      <div class="form-group">
        <label for="message" class="form-label">
          <i class="fas fa-comment-dots me-1"></i> Message <span style="color: #ef4444;">*</span>
        </label>
        <textarea 
          name="message" 
          id="message" 
          class="form-control-custom @error('message') is-invalid @enderror" 
          placeholder="Écrivez votre message ici..."
          required
          maxlength="1000">{{ old('message') }}</textarea>
        <div class="char-counter">
          <span id="charCount">0</span> / 1000 caractères
        </div>
        @error('message')
          <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
      </div>
      
      <!-- Actions -->
      <div class="actions-row">
        <a href="{{ route('messages.index') }}" class="btn-cancel">
          <i class="fas fa-times me-1"></i> Annuler
        </a>
        <button type="submit" class="btn-submit">
          <i class="fas fa-paper-plane me-1"></i> Envoyer
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  // Compteur de caractères
  const messageTextarea = document.getElementById('message');
  const charCount = document.getElementById('charCount');
  
  if (messageTextarea && charCount) {
    messageTextarea.addEventListener('input', function() {
      const count = this.value.length;
      charCount.textContent = count;
      
      if (count > 900) {
        charCount.style.color = '#ef4444';
      } else if (count > 700) {
        charCount.style.color = '#f59e0b';
      } else {
        charCount.style.color = '#94a3b8';
      }
    });
    
    // Initialiser le compteur
    charCount.textContent = messageTextarea.value.length;
  }
  
  // Animation du bouton submit
  const form = document.getElementById('messageForm');
  const submitBtn = form.querySelector('.btn-submit');
  
  form.addEventListener('submit', function(e) {
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Envoi en cours...';
    submitBtn.disabled = true;
  });
  
  // Focus automatique sur le select
  const receiverSelect = document.getElementById('receiver_id');
  if (receiverSelect) {
    receiverSelect.focus();
  }
</script>
@endpush
@endsection