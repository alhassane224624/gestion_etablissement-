@extends( $layout ?? 'layouts.app')
@section('title', 'Conversation')

@section('content')
<style>
  .chat-wrap{ max-width:1100px; margin:0 auto; }
  .chat-card{ background:#0b1220; border-radius:16px; border:1px solid rgba(148,163,184,.15); box-shadow:0 10px 30px rgba(0,0,0,.35); overflow:hidden; }

  .chat-header{ background:linear-gradient(135deg,#0b1222,#111826); padding:14px 18px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(148,163,184,.15); }
  .who{ display:flex; align-items:center; gap:10px; color:#e2e8f0; font-weight:700; }
  .who .avatar{ width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#6366f1,#14b8a6); display:flex; align-items:center; justify-content:center; color:white; font-weight:800; }
  .chat-header .actions form button,
  .chat-header .actions a{ background:#1f2937; border:1px solid rgba(148,163,184,.18); color:#e5e7eb; border-radius:10px; padding:.45rem .8rem; font-weight:600; }
  .chat-header .actions form button:hover,
  .chat-header .actions a:hover{ background:#334155; }

  .chat-body{ padding:18px; height:68vh; overflow-y:auto; background:#0f172a; }
  .msg{ margin-bottom:10px; display:flex; }
  .msg.you{ justify-content:flex-end; }
  .bubble{
    max-width:66%; padding:.6rem .9rem; border-radius:16px; color:white; position:relative; animation:fadeIn .25s ease;
  }
  .me-bubble{ background:linear-gradient(135deg,#3b82f6,#6366f1); border-top-right-radius:4px; }
  .them-bubble{ background:#1f2937; border-top-left-radius:4px; }
  .time{ font-size:.72rem; color:#cbd5e1; margin-top:4px; text-align:right; }

  .chat-footer{ background:#0b1220; border-top:1px solid rgba(148,163,184,.15); padding:10px; position:sticky; bottom:0; }
  .composer{ display:flex; gap:8px; }
  .composer input{
    flex:1; background:#0f172a; color:#e5e7eb; border:1px solid rgba(148,163,184,.2); border-radius:12px; padding:.75rem .9rem; outline:none;
  }
  .btn-send{ background:linear-gradient(135deg,#3b82f6,#6366f1); border:none; color:white; border-radius:12px; padding:.75rem 1rem; font-weight:700; }
  .btn-send:hover{ filter:brightness(1.05); transform:translateY(-1px); }
  @keyframes fadeIn { from {opacity:0; transform:translateY(6px)} to {opacity:1; transform:none} }
</style>

<div class="chat-wrap">
  <div class="chat-card">
    <div class="chat-header">
      <div class="who">
        <div class="avatar">{{ strtoupper(substr($contact->name,0,1)) }}</div>
        <div>
          <div>{{ $contact->name }}</div>
          <small style="color:#94a3b8">{{ $contact->email ?? '' }}</small>
        </div>
      </div>

      <div class="actions d-flex gap-2">
        <a href="{{ route('messages.index') }}"><i class="fa-solid fa-inbox"></i> Boîte</a>

        {{-- supprimer la conversation --}}
        <form action="{{ route('messages.delete', $contact->id) }}" method="POST"
              onsubmit="return confirm('Supprimer la conversation avec {{ $contact->name }} ?')">
          @csrf @method('DELETE')
          <button type="submit"><i class="fa-solid fa-trash-can"></i> Supprimer</button>
        </form>
      </div>
    </div>

    <div id="messages-container" class="chat-body">
      @forelse($messages as $m)
        <div class="msg {{ $m->sender_id === auth()->id() ? 'you' : '' }}">
          <div class="bubble {{ $m->sender_id === auth()->id() ? 'me-bubble' : 'them-bubble' }}">
            <div>{{ $m->message ?? $m->body }}</div>
            <div class="time">{{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y H:i') }}</div>
          </div>
        </div>
      @empty
        <div class="text-center" style="color:#94a3b8">Aucun message pour le moment.</div>
      @endforelse
    </div>

    <div class="chat-footer">
      <form class="composer" method="POST" action="{{ route('messages.send', ['user' => $contact->id]) }}">
        @csrf
        <input type="text" name="message" id="messageInput" placeholder="Écrire un message..." required autocomplete="off">
        <button class="btn-send" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Auto scroll
  const container = document.getElementById('messages-container');
  if (container) container.scrollTop = container.scrollHeight;

  // Envoi avec Enter
  const input = document.getElementById('messageInput');
  if (input) {
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        e.target.form.submit();
      }
    });
  }
</script>
@endpush
@endsection
