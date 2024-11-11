<?php
$class = match ($method) {
    'Create' => 'bg-green-500',
    'Update' => 'bg-blue-600',
    'Delete' => 'bg-red-500',
}
?>

<span class="text-white rounded-md px-2 py-1 {{ $class }}">
    {{ $method }}
</span>
