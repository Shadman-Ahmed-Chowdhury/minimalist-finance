@props(['title', 'value', 'icon', 'color', 'bgColor'])


<div class="p-6  rounded-xl shadow-sm border border-gray-100 {{ $bgColor }}">
    <h3 class="text-sm font-medium {{ $color }}">{{ $title }}</h3>
    <p class="text-2xl font-semibold mt-2 {{ $color }}">${{ $value }}</p>
</div>
