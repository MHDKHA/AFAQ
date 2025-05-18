@php use Illuminate\Support\Facades\App; @endphp
<x-filament-panels::page>
    {{-- Header widgets (if any) --}}
    @if ($this->hasHeaderWidgets)
        <x-filament-widgets::widgets
            :columns="$this->getHeaderWidgetsColumns()"
            :widgets="$this->getHeaderWidgets()"
            :data="$this->getWidgetsData()"
        />
    @endif
@php

      $locale = session('locale', 'ar');
          App::setLocale($locale);
          session(['locale' => $locale]);

          @endphp
    {{-- Questions table --}}
    <div class="p-4 bg-white dark:bg-gray-900 rounded-lg shadow-sm overflow-auto">
        <table class="table-fixed w-full border-collapse">
            <thead>
            <tr class="bg-gray-100 dark:bg-gray-800">
                <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-gray-700 dark:text-gray-200">
                    #
                </th>
                <th class="border border-gray-200 dark:border-gray-700 px-2 py-1 text-gray-700 dark:text-gray-200">
                    {{__('assessment.view.questions')}}
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($criteria as $categoryName => $group)
                <tr>
                    <td
                        colspan="2"
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
                            @if($locale == 'ar') {{ $criterion->question_ar }}@endif @if($locale == 'en') {{ $criterion->question }} @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>



</x-filament-panels::page>
