

@php

    use Illuminate\Support\Facades\App;
    $locale = session('locale', 'ar');

        App::setLocale($locale);
        session(['locale' => $locale]);

@endphp
<x-filament-panels::page>

    <div class="p-4 bg-white dark:bg-gray-900 rounded-lg shadow-sm overflow-auto">
        <table class="table-fixed w-full border-collapse">
            <thead>
            <tr class="bg-gray-100 dark:bg-gray-800">
                <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-gray-700 dark:text-gray-200">#
                </th>
                <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-gray-700 dark:text-gray-200">{{__('assessment.view.questions')}}</th>
                <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-gray-700 dark:text-gray-200">{{__('assessment.fill.available')}}</th>
                <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-gray-700 dark:text-gray-200">{{__('assessment.fill.notes')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($criteria as $categoryName => $group)
                <tr>
                    <td colspan="4"
                        class="border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 font-bold text-center text-gray-700 dark:text-gray-200"
                    >
                        {{ $categoryName }}
                    </td>
                </tr>
                @foreach($group as $criterion)
                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800">
                        <td class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-center text-gray-700 dark:text-gray-200">
                            #{{ $criterion->order }}
                        </td>
                        <td class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-gray-700 dark:text-gray-200">
                            @if($locale == 'ar') {{ $criterion->question_ar }}@endif @if($locale == 'en')   {{ $criterion->question }} @endif
                        </td>
                        <td class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-center">
                            <input
                                type="checkbox"
                                wire:model="formResponses.{{ $criterion->id }}.is_available"
                                class="h-4 w-4 text-primary-600 dark:text-primary-400"
                            />
                        </td>
                        <td class="border border-gray-200 dark:border-gray-700 px-2 py-1">
                                <textarea
                                    wire:model="formResponses.{{ $criterion->id }}.notes"
                                    class="w-full h-16 resize-none border rounded px-1 py-1
                                           border-gray-200 dark:border-gray-700
                                           bg-white dark:bg-gray-900
                                           text-gray-700 dark:text-gray-200"
                                ></textarea>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-4 flex justify-end">
        <x-filament::button color="primary" wire:click="save">
            {{ __('assessment.fill.save') }}
        </x-filament::button>
    </div>

    @if ($this->hasFooterWidgets)
        <div class="mt-8">
            <x-filament-widgets::widgets
                :columns="$this->getFooterWidgetsColumns()"
                :widgets="$this->getFooterWidgets()"
                :data="$this->getWidgetsData()"
            />
        </div>
    @endif
</x-filament-panels::page>
