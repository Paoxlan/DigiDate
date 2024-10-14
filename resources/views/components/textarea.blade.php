@props([
    'text' => '',
    'max' => null,
    'name' => null,
])

@php
    $id = $name ?? rand();
    $textLength = strlen($text);
@endphp

<div class="relative">
    <textarea id="{{ $id }}-textarea" {{ $name ? "name='$name'" : '' }} {{ $max ? "maxlength=$max" : '' }}
            {{ $attributes->merge(['class' => 'w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>{{ $text }}</textarea>
    <p id="{{ $id }}-text-count"
       class="absolute bottom-1 right-2 text-gray-500">{{ $max ? "$textLength / $max" : '' }}</p>
    @if($max)
        <script class="text/javascript">
            {
                const textarea = document.getElementById('{{ $id }}-textarea');
                const textCount = document.getElementById('{{ $id }}-text-count');

                textarea.addEventListener('input', () => {
                    const text = textarea.value;
                    textCount.textContent = `${text.length} / {{ $max }}`;
                });
            }
        </script>
    @endif
</div>
