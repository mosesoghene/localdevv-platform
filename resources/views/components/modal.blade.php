@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    id="modal-{{ $name }}"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 {{ $show ? '' : 'hidden' }}"
    style="display: {{ $show ? 'block' : 'none' }};"
>
    <div
        class="fixed inset-0 transform transition-all bg-gray-500 opacity-0"
        onclick="closeModal('{{ $name }}')"
        id="modal-{{ $name }}-backdrop"
    >
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div
        id="modal-{{ $name }}-content"
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        {{ $slot }}
    </div>
</div>

<script>
    function openModal(name) {
        const modal = document.getElementById('modal-' + name);
        const backdrop = document.getElementById('modal-' + name + '-backdrop');
        const content = document.getElementById('modal-' + name + '-content');
        
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.classList.add('overflow-y-hidden');
            
            // Trigger animation
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                content.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
                content.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }
    }
    
    function closeModal(name) {
        const modal = document.getElementById('modal-' + name);
        const backdrop = document.getElementById('modal-' + name + '-backdrop');
        const content = document.getElementById('modal-' + name + '-content');
        
        if (modal) {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            content.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            content.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.classList.remove('overflow-y-hidden');
            }, 200);
        }
    }
    
    // Listen for open/close modal events
    window.addEventListener('open-modal', function(event) {
        openModal(event.detail);
    });
    
    window.addEventListener('close-modal', function(event) {
        closeModal(event.detail);
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    const name = modal.id.replace('modal-', '');
                    closeModal(name);
                }
            });
        }
    });
</script>
