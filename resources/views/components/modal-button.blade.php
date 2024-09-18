@props(['for'])

@php
    $btnId = $for . '_btn';
@endphp

<button {{ $attributes->merge([
    'type' => 'button',
    'id' => $btnId
])}}>
    {{ $slot }}
    <script type="text/javascript">
        {
            const btn = document.getElementById("{{ $btnId }}");
            const modalId = "{{ $for }}";

            let modal;
            btn.addEventListener("click", () => {
                modal = modal || document.getElementById(modalId);

                const ariaHidden = modal.getAttribute("aria-hidden");

                modal.setAttribute("aria-hidden", ariaHidden === "true" ? "false" : true);
            });
        }
    </script>
</button>
