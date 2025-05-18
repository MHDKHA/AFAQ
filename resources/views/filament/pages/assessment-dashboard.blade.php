<!-- resources/views/filament/pages/assessment-dashboard.blade.php -->
<x-filament::page>
{{--    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">--}}
{{--        <!-- Assessment Availability Chart - Pie Chart -->--}}
{{--        <div class="p-4 bg-white rounded-xl shadow dark:bg-gray-800">--}}
{{--            <h2 class="text-lg font-bold mb-4 text-right">حالة توفر المعايير</h2>--}}
{{--            <div class="h-80" id="availability-chart"></div>--}}
{{--        </div>--}}

{{--        <!-- Domains Distribution Chart - Bar Chart -->--}}
{{--        <div class="p-4 bg-white rounded-xl shadow dark:bg-gray-800">--}}
{{--            <h2 class="text-lg font-bold mb-4 text-right">توزيع المعايير حسب المجالات</h2>--}}
{{--            <div class="h-80" id="domains-chart"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="grid grid-cols-1 gap-6 mb-6">--}}
{{--        <!-- Criteria Radar Chart -->--}}
{{--        <div class="p-4 bg-white rounded-xl shadow dark:bg-gray-800">--}}
{{--            <h2 class="text-lg font-bold mb-4 text-right">مستوى تقييم المعايير</h2>--}}
{{--            <div class="h-96" id="radar-chart"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">--}}
{{--        <!-- Additional Charts or Stats Cards as needed -->--}}
{{--        <div class="p-4 bg-white rounded-xl shadow dark:bg-gray-800">--}}
{{--            <h2 class="text-lg font-bold mb-4 text-right">نسبة اكتمال التقييم</h2>--}}
{{--            <div class="h-80" id="completion-chart"></div>--}}
{{--        </div>--}}

{{--        <div class="p-4 bg-white rounded-xl shadow dark:bg-gray-800">--}}
{{--            <h2 class="text-lg font-bold mb-4 text-right">تأثير العلامة التجارية</h2>--}}
{{--            <div class="h-80" id="brand-impact-chart"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Required Scripts -->--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>--}}

{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function() {--}}
{{--            // Availability Chart - Pie--}}
{{--            const availabilityOptions = {--}}
{{--                chart: {--}}
{{--                    type: 'pie',--}}
{{--                    height: 300,--}}
{{--                    toolbar: {--}}
{{--                        show: false--}}
{{--                    },--}}
{{--                    animations: {--}}
{{--                        enabled: true--}}
{{--                    }--}}
{{--                },--}}
{{--                series: [{{ $assessment->getAvailableCountAttribute() }}, {{ $assessment->getUnavailableCountAttribute() }}],--}}
{{--                labels: ['نعم', 'لا'],--}}
{{--                legend: {--}}
{{--                    position: 'bottom'--}}
{{--                },--}}
{{--                colors: ['#10B981', '#EF4444'],--}}
{{--                responsive: [{--}}
{{--                    breakpoint: 480,--}}
{{--                    options: {--}}
{{--                        chart: {--}}
{{--                            width: 200--}}
{{--                        },--}}
{{--                        legend: {--}}
{{--                            position: 'bottom'--}}
{{--                        }--}}
{{--                    }--}}
{{--                }]--}}
{{--            };--}}

{{--            const availabilityChart = new ApexCharts(document.querySelector("#availability-chart"), availabilityOptions);--}}
{{--            availabilityChart.render();--}}

{{--            // Domain Distribution Chart - Bar--}}
{{--            const domainsOptions = {--}}
{{--                chart: {--}}
{{--                    type: 'bar',--}}
{{--                    height: 350,--}}
{{--                    stacked: true,--}}
{{--                    toolbar: {--}}
{{--                        show: false--}}
{{--                    },--}}
{{--                    animations: {--}}
{{--                        enabled: true--}}
{{--                    }--}}
{{--                },--}}
{{--                plotOptions: {--}}
{{--                    bar: {--}}
{{--                        horizontal: false,--}}
{{--                        columnWidth: '55%',--}}
{{--                    }--}}
{{--                },--}}
{{--                dataLabels: {--}}
{{--                    enabled: false--}}
{{--                },--}}
{{--                stroke: {--}}
{{--                    show: true,--}}
{{--                    width: 2,--}}
{{--                    colors: ['transparent']--}}
{{--                },--}}
{{--                xaxis: {--}}
{{--                    categories: {!! json_encode(App\Models\Domain::all()->pluck('name')) !!},--}}
{{--                },--}}
{{--                yaxis: {--}}
{{--                    title: {--}}
{{--                        text: 'عدد المعايير'--}}
{{--                    }--}}
{{--                },--}}
{{--                fill: {--}}
{{--                    opacity: 1--}}
{{--                },--}}
{{--                colors: ['#10B981', '#EF4444'],--}}
{{--                legend: {--}}
{{--                    position: 'bottom'--}}
{{--                },--}}
{{--                series: [--}}
{{--                    {--}}
{{--                        name: 'نعم',--}}
{{--                        data: {!! json_encode(App\Models\Domain::all()->map(function($domain) use ($assessment) {--}}
{{--                            $criteriaIds = App\Models\Criterion::where('domain_id', $domain->id)->pluck('id')->toArray();--}}
{{--                            return App\Models\AssesmentItem::where('assessment_id', $assessment->id)--}}
{{--                                ->whereIn('criteria_id', $criteriaIds)--}}
{{--                                ->where('is_available', true)--}}
{{--                                ->count();--}}
{{--                        })) !!}--}}
{{--                    },--}}
{{--                    {--}}
{{--                        name: 'لا',--}}
{{--                        data: {!! json_encode(App\Models\Domain::all()->map(function($domain) use ($assessment) {--}}
{{--                            $criteriaIds = App\Models\Criterion::where('domain_id', $domain->id)->pluck('id')->toArray();--}}
{{--                            return App\Models\AssesmentItem::where('assessment_id', $assessment->id)--}}
{{--                                ->whereIn('criteria_id', $criteriaIds)--}}
{{--                                ->where('is_available', false)--}}
{{--                                ->count();--}}
{{--                        })) !!}--}}
{{--                    }--}}
{{--                ]--}}
{{--            };--}}

{{--            const domainsChart = new ApexCharts(document.querySelector("#domains-chart"), domainsOptions);--}}
{{--            domainsChart.render();--}}

{{--            // Radar Chart for Criteria--}}
{{--            const radarOptions = {--}}
{{--                chart: {--}}
{{--                    height: 350,--}}
{{--                    type: 'radar',--}}
{{--                    toolbar: {--}}
{{--                        show: false--}}
{{--                    },--}}
{{--                    animations: {--}}
{{--                        enabled: true--}}
{{--                    }--}}
{{--                },--}}
{{--                series: [{--}}
{{--                    name: 'مستوى التقييم',--}}
{{--                    data: {!! json_encode(array_fill(0, App\Models\Category::count(), 3)) !!} // Sample data, adjust as needed--}}
{{--                }],--}}
{{--                labels: {!! json_encode(App\Models\Category::all()->pluck('name')) !!},--}}
{{--                plotOptions: {--}}
{{--                    radar: {--}}
{{--                        size: 140,--}}
{{--                        polygons: {--}}
{{--                            strokeColors: '#e9e9e9',--}}
{{--                            fill: {--}}
{{--                                colors: ['#f8f8f8', '#fff']--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                },--}}
{{--                colors: ['#3B82F6'],--}}
{{--                markers: {--}}
{{--                    size: 4,--}}
{{--                    colors: ['#3B82F6'],--}}
{{--                    strokeWidth: 2,--}}
{{--                },--}}
{{--                tooltip: {--}}
{{--                    y: {--}}
{{--                        formatter: function(val) {--}}
{{--                            return val;--}}
{{--                        }--}}
{{--                    }--}}
{{--                },--}}
{{--                yaxis: {--}}
{{--                    tickAmount: 3,--}}
{{--                    min: 0,--}}
{{--                    max: 3,--}}
{{--                }--}}
{{--            };--}}

{{--            const radarChart = new ApexCharts(document.querySelector("#radar-chart"), radarOptions);--}}
{{--            radarChart.render();--}}

{{--            // Completion Percentage Chart - Radial--}}
{{--            const completionOptions = {--}}
{{--                chart: {--}}
{{--                    height: 300,--}}
{{--                    type: 'radialBar',--}}
{{--                    animations: {--}}
{{--                        enabled: true--}}
{{--                    }--}}
{{--                },--}}
{{--                plotOptions: {--}}
{{--                    radialBar: {--}}
{{--                        hollow: {--}}
{{--                            size: '70%',--}}
{{--                        },--}}
{{--                        dataLabels: {--}}
{{--                            show: true,--}}
{{--                            name: {--}}
{{--                                offsetY: -10,--}}
{{--                                show: true,--}}
{{--                                color: '#888',--}}
{{--                                fontSize: '18px'--}}
{{--                            },--}}
{{--                            value: {--}}
{{--                                color: '#111',--}}
{{--                                fontSize: '30px',--}}
{{--                                show: true,--}}
{{--                                formatter: function(val) {--}}
{{--                                    return val + '%';--}}
{{--                                }--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                },--}}
{{--                fill: {--}}
{{--                    colors: ['#3B82F6']--}}
{{--                },--}}
{{--                series: [{{ $assessment->getCompletionPercentageAttribute() }}],--}}
{{--                labels: ['نسبة الإكتمال'],--}}
{{--            };--}}

{{--            const completionChart = new ApexCharts(document.querySelector("#completion-chart"), completionOptions);--}}
{{--            completionChart.render();--}}

{{--            // Brand Impact Chart - Donut--}}
{{--            const brandImpactOptions = {--}}
{{--                chart: {--}}
{{--                    type: 'donut',--}}
{{--                    height: 300--}}
{{--                },--}}
{{--                series: [33, 33, 33], // Placeholder data - adjust as needed--}}
{{--                labels: ['مرتفع', 'متوسط', 'منخفض'],--}}
{{--                colors: ['#10B981', '#F59E0B', '#EF4444'],--}}
{{--                plotOptions: {--}}
{{--                    pie: {--}}
{{--                        donut: {--}}
{{--                            size: '65%',--}}
{{--                            labels: {--}}
{{--                                show: true,--}}
{{--                                total: {--}}
{{--                                    show: true,--}}
{{--                                    showAlways: true,--}}
{{--                                    label: 'الإجمالي',--}}
{{--                                    fontSize: '16px',--}}
{{--                                    fontWeight: 600,--}}
{{--                                }--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                }--}}
{{--            };--}}

{{--            const brandImpactChart = new ApexCharts(document.querySelector("#brand-impact-chart"), brandImpactOptions);--}}
{{--            brandImpactChart.render();--}}
{{--        });--}}
{{--    </script>--}}
</x-filament::page>
