@props([
    'id' => 'periodIdInput',
    'label' => 'Periode Seleksi',
    'width' => 'w-32',
    'height' => 'h-9',
])

<div>
    <label for="{{ $id }}" class="mb-1 block text-xs font-bold text-slate-600">
        {{ $label }}
    </label>

    <div class="relative {{ $width }}">
        <select
            id="{{ $id }}"
            {{ $attributes->merge([
                'class' => "{$height} w-full appearance-none rounded-md border border-slate-300 bg-white px-3 pr-9 text-sm font-semibold text-slate-800 outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
            ]) }}
        >
            <option value="1">2026</option>
        </select>

        <svg
            class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"
            viewBox="0 0 24 24"
            fill="none"
        >
            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
</div>