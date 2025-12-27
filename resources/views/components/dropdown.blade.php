@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};

$dropdownId = 'dropdown-' . uniqid();
@endphp

<div class="relative" id="{{ $dropdownId }}-wrapper">
    <div onclick="toggleDropdown('{{ $dropdownId }}')">
        {{ $trigger }}
    </div>

    <div id="{{ $dropdownId }}"
            class="hidden absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }} transition-all duration-200 opacity-0 scale-95">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const isHidden = dropdown.classList.contains('hidden');
        
        // Close all dropdowns first
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            if (d.id !== id) {
                d.classList.add('hidden');
                d.classList.remove('opacity-100', 'scale-100');
                d.classList.add('opacity-0', 'scale-95');
            }
        });
        
        if (isHidden) {
            dropdown.classList.remove('hidden');
            setTimeout(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            }, 10);
        } else {
            dropdown.classList.remove('opacity-100', 'scale-100');
            dropdown.classList.add('opacity-0', 'scale-95');
            setTimeout(() => dropdown.classList.add('hidden'), 200);
        }
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
        dropdowns.forEach(dropdown => {
            const wrapper = document.getElementById(dropdown.id + '-wrapper');
            if (wrapper && !wrapper.contains(event.target)) {
                dropdown.classList.remove('opacity-100', 'scale-100');
                dropdown.classList.add('opacity-0', 'scale-95');
                setTimeout(() => dropdown.classList.add('hidden'), 200);
            }
        });
    });
</script>
