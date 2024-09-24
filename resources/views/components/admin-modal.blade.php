@props(['id'])

<div id="{{ $id }}" aria-hidden="false"
     class="absolute w-screen h-screen top-0 left-0 transition-opacity aria-hidden:opacity-100 opacity-0
     aria-hidden:z-50 -z-50"
>
    <div class="w-full h-full bg-slate-900 opacity-75">

    </div>
    <div class="absolute top-0 left-0 w-full flex justify-center">
        <div id="modal_slot" {{ $attributes->merge([
        'class' => "lg:max-w-2xl w-full bg-gray-800 rounded-lg px-2 py-4 mt-32"
        ]) }}>
            {{ $slot }}
        </div>
    </div>

    <script>
        {
            const modal = document.getElementById("{{ $id }}");

            modal.addEventListener("click", (event) => {
                if (event.target?.id === "modal_slot") return;

                modal.setAttribute("aria-hidden", "false");
            });
        }
    </script>
</div>
