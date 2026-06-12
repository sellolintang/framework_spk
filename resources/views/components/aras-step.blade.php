<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4">
        <h2 class="text-lg font-bold text-slate-900">{{ $title }}</h2>
        <p class="mt-1 text-sm text-slate-600">{{ $description }}</p>
    </div>

    {{ $slot }}
</div>