{{-- resources/views/filament/resources/assessment-resource/widgets/domain-selector-widget.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium">تحديد المجال</h2>
        </div>

        <div class="mt-2">
            {{ $this->form }}
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
