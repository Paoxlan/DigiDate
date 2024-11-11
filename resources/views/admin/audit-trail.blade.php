<x-app-layout>
    <div class="py-8 flex flex-col items-center">
        <div class="gray-700 dark:text-gray-300 dark:bg-gray-800 lg:max-w-7xl w-full py-2 px-4 rounded-md">
            <section class="text-2xl">
                <h1>Record #{{ $auditTrail->id }}</h1>
                <h1>Model: {{ $auditTrail->class }}</h1>
            </section>

            <p class="my-2">
                Method: <x-audit-trail-method :method="$auditTrail->method" />
            </p>

            <div class="flex justify-between gap-x-4">
                @isset($auditTrail->old_model)
                    <?php $oldModel = json_decode($auditTrail->old_model) ?>
                    <div class="flex-grow">
                        <h1 class="text-xl font-bold">{{ __('Old model') . '(s)' }}</h1>
                        <?php
                            if (is_array($oldModel)) {
                                dump(...$oldModel);
                            } else {
                                dump($oldModel);
                            }
                        ?>
                    </div>
                @endisset

                <div class="flex-grow">
                    <?php $model = json_decode($auditTrail->model); ?>
                    <h1 class="text-xl font-bold {{ isset($auditTrail->old_model) ? 'text-right' : '' }}">
                        {{ (isset($auditTrail->old_model) ? __('Updated model') : __('Model')) . '(s)' }}
                    </h1>
                    <?php
                        if (is_array($model)) {
                            dump(...$model);
                        } else {
                            dump($model);
                        }
                    ?>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
