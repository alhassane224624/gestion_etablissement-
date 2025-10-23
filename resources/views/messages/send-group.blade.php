@extends($layout ?? 'layouts.app')
@section('title', 'Message Groupé')

@section('content')
<style>
  .group-message-container { max-width: 900px; margin: 0 auto; }
  
  .header-card {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
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
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
  }
  
  select.form-control-custom {
    cursor: pointer;
  }
  
  textarea.form-control-custom {
    resize: vertical;
    min-height: 150px;
  }
  
  .btn-submit {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
    box-shadow: 0 5px 20px rgba(16, 185, 129, 0.4);
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
  
  .filter-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }
  
  .filter-card {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
  }
  
  .filter-card.active {
    border-color: #10b981;
    background: #d1fae5;
  }
  
  .info-box {
    background: #dbeafe;
    border-left: 4px solid #3b82f6;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
  }
  
  .info-box i {
    color: #3b82f6;
  }
  
  .warning-box {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
  }
  
  .warning-box i {
    color: #f59e0b;
  }
</style>

<div class="group-message-container">
  <!-- Header -->
  <div class="header-card">
    <h1><i class="fas fa-users me-2"></i> Message Groupé</h1>
    <p class="mb-0">Envoyer un message à plusieurs stagiaires en même temps</p>
  </div>
  
  <!-- Info Box -->
  <div class="info-box">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Information:</strong> Sélectionnez une filière OU une classe pour cibler vos destinataires.
  </div>
  
  <!-- Form -->
  <div class="form-card">
    <form action="{{ route('messages.send-group') }}" method="POST" id="groupMessageForm">
      @csrf
      
      <!-- Filtres -->
      <div class="filter-cards">
        <!-- Filière -->
        <div class="form-group">
          <label for="filiere_id" class="form-label">
            <i class="fas fa-graduation-cap me-1"></i> Par Filière
          </label>
          <select name="filiere_id" id="filiere_id" class="form-control-custom @error('filiere_id') is-invalid @enderror">
            <option value="">-- Toutes les filières --</option>
            @foreach($filieres as $filiere)
              <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                {{ $filiere->nom }}
              </option>
            @endforeach
          </select>
          @error('filiere_id')
            <div class="text-danger mt-2">{{ $message }}</div>
          @enderror
        </div>
        
        <!-- Classe -->
        <div class="form-group">
          <label for="classe_id" class="form-label">
            <i class="fas fa-school me-1"></i> Par Classe
          </label>
          <select name="classe_id" id="classe_id" class="form-control-custom @error('classe_id') is-invalid @enderror">
            <option value="">-- Toutes les classes --</option>
            @foreach($classes as $classe)
              <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                {{ $classe->nom }} - {{ $classe->filiere->nom ?? 'N/A' }}
              </option>
            @endforeach
          </select>
          @error('classe_id')
            <div class="text-danger mt-2">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <!-- Warning -->
      <div class="warning-box">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Attention:</strong> Si vous sélectionnez les deux, seule la classe sera prise en compte.
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
      
      <!-- Preview -->
      <div id="previewBox" style="display: none;">
        <div class="info-box">
          <i class="fas fa-eye me-2"></i>
          <strong>Aperçu:</strong>
          <div id="previewText" style="margin-top: 0.5rem; font-style: italic;"></div>
        </div>
      </div>
      
      <!-- Actions -->
      <div class="actions-row">
        <a href="{{ route('messages.index') }}" class="btn-cancel">
          <i class="fas fa-times me-1"></i> Annuler
        </a>
        <button type="submit" class="btn-submit">
          <i class="fas fa-paper-plane me-1"></i> Envoyer à Tous
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
  const previewBox = document.getElementById('previewBox');
  const previewText = document.getElementById('previewText');
  
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
      
      // Aperçu
      if (this.value.trim() !== '') {
        previewBox.style.display = 'block';
        previewText.textContent = this.value;
      } else {
        previewBox.style.display = 'none';
      }
    });
    
    // Initialiser le compteur
    charCount.textContent = messageTextarea.value.length;
  }
  
  // Gestion des filtres
  const filiereSelect = document.getElementById('filiere_id');
  const classeSelect = document.getElementById('classe_id');
  
  filiereSelect.addEventListener('change', function() {
    if (this.value !== '') {
      classeSelect.value = '';
    }
  });
  
  classeSelect.addEventListener('change', function() {
    if (this.value !== '') {
      filiereSelect.value = '';
    }
  });
  
  // Animation du bouton submit
  const form = document.getElementById('groupMessageForm');
  const submitBtn = form.querySelector('.btn-submit');
  
  form.addEventListener('submit', function(e) {
    const filiereId = filiereSelect.value;
    const classeId = classeSelect.value;
    const message = messageTextarea.value.trim();
    
    if (filiereId === '' && classeId === '') {
      e.preventDefault();
      alert('⚠️ Veuillez sélectionner au moins une filière ou une classe !');
      return;
    }
    
    if (message === '') {
      e.preventDefault();
      alert('⚠️ Le message ne peut pas être vide !');
      return;
    }
    
    const confirm = window.confirm('📧 Êtes-vous sûr de vouloir envoyer ce message à tous les stagiaires sélectionnés ?');
    
    if (!confirm) {
      e.preventDefault();
      return;
    }
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Envoi en cours...';
    submitBtn.disabled = true;
  });
</script>
@endpush
@endsection