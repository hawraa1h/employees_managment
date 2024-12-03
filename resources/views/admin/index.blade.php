@extends('admin.main')

@if(hasRole('admin'))
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-dashboard"></i> {{__('Dashboard')}}</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">{{__('Dashboard')}}</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                    <div class="info">
                        <h4>الموظفيين</h4>
                        <p><b>{{ $employeeCount }}</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small info coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                    <div class="info">
                        <h4>مهام السياسات</h4>
                        <p><b>{{ \App\Models\Policy::where('type', 'policy')->count() }}</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small danger coloured-icon"><i class="icon fa fa-calendar fa-3x"></i>
                    <div class="info">
                        <h4>مهام المعايير</h4>
                        <p><b>{{ \App\Models\Policy::where('type', 'standard')->count() }}</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-star fa-3x"></i>
                    <div class="info">
                        <h4>الإدارات</h4>
                        <p><b>{{ $departmentCount }}</b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">{{ __('السياسات') }}</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="departmentEmployeeChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tile">
                    <h3 class="tile-title">{{ __('المعايير') }}</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="departmentEmployeeChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">مهام قيد التدقيق & المراجعة</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{ __('المهمه') }}</th>
                                <th>{{ __('تاريخ المراجعه') }}</th>
                                <th>{{ __('تاريخ التدقيق') }}</th>
                                <th>{{ __('تم المراجعه؟') }}</th>
                                <th>{{ __('تم التدقيق ؟') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tasks as $task)
                                @if(!$task->reviewed_at || !$task->audited_at)
                                    <tr>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->reviewed_at ? $task->reviewed_at :'--' }}</td>
                                        <td>{{ $task->audited_at ? $task->audited_at : ' --' }}</td>
                                        <td class="text-center">
                                            @if($task->reviewed_at)
                                                <i class="fa fa-check-circle text-success"></i> <br>
                                                {{ optional($task->reviewer)->name  }}
                                            @else
                                                <i class="fa fa-times text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($task->audited_at)
                                                <i class="fa fa-check-circle text-success"></i> <br>
                                                {{ optional($task->auditor)->name  }}
                                            @else
                                                <i class="fa fa-times text-danger"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">المهام المنجزة</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{ __('المهمه') }}</th>
                                <th>{{ __('تاريخ المراجعه') }}</th>
                                <th>{{ __('تاريخ التدقيق') }}</th>
                                <th>{{ __('المراجعه بواسطة') }}</th>
                                <th>{{ __('التدقيق بواسطة') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tasks as $task)
                                @if($task->reviewed_at && $task->audited_at)
                                    <tr>
                                        <td>{{ $task->title }}</td>
                                        <td class="text-center"><i class="fa fa-check-circle text-success"></i> {{$task->reviewed_at }}</td>
                                        <td class="text-center"><i class="fa fa-check-circle text-success"></i> {{$task->audited_at }}</td>
                                        <td class="text-center">
                                            {{ optional($task->reviewer)->name  }}
                                        </td>

                                        <td class="text-center">
                                            {{ optional($task->auditor)->name  }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    </main>
@endsection

@push('chart')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var departmentData = @json($departmentChartData);
        var ctxl = document.getElementById("departmentEmployeeChart").getContext("2d");

        var chartLabels = Object.keys(departmentData); // Department names
        var chartValues = Object.values(departmentData); // Employee count for each department
        var lineChart = new Chart(ctxl, {
            type: 'doughnut', // Define the type of chart
            data: {
                labels: chartLabels, // X-axis labels (Department names)
                datasets: [{
                    label: 'السياسات', // Chart label
                    data: chartValues, // Y-axis data (Employee counts)
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Line fill color
                    borderColor: 'rgba(75, 192, 192, 1)', // Line border color
                    borderWidth: 2, // Line width
                    fill: true // Fill the area under the line
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true // Start y-axis at zero
                    }
                }
            }
        });



        var ctxl2 = document.getElementById("departmentEmployeeChart2").getContext("2d");

        var chartLabels2 = Object.keys(departmentData); // Department names
        var chartValues2 = Object.values(departmentData); // Employee count for each department
        var lineChart2 = new Chart(ctxl2, {
            type: 'doughnut', // Define the type of chart
            data: {
                labels: chartLabels2, // X-axis labels (Department names)
                datasets: [{
                    label: 'السياسات', // Chart label
                    data: chartValues, // Y-axis data (Employee counts)
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Line fill color
                    borderColor: 'rgba(75, 192, 192, 1)', // Line border color
                    borderWidth: 2, // Line width
                    fill: true // Fill the area under the line
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true // Start y-axis at zero
                    }
                }
            }
        });
    </script>
@endpush
@else
    @section('content')
        <main class="app-content">
            <div class="app-title">
                <div>
                    <h1><i class="fa fa-dashboard"></i> {{__('Dashboard')}}</h1>
                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                    <li class="breadcrumb-item"><a href="#">{{__('Dashboard')}}</a></li>
                </ul>
            </div>
            <div class="row">
                @if($unreviewedCount > 0)
                   <div class="col-md-12">
                       <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <h3>   <strong>تنبيه!</strong> لديك {{ $unreviewedCount }} سياسة ومعايير بحاجة إلى المراجعة.</h3>
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                       </div>
                   </div>
                @endif
                @if(!hasRole('normal'))
                <div class="col-md-12">
                    <div class="tile">
                        <h3 class="tile-title">مهام قيد التدقيق & المراجعة</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('المهمه') }}</th>
                                    <th>{{ __('تاريخ المراجعه') }}</th>
                                    <th>{{ __('تاريخ التدقيق') }}</th>
                                    <th>{{ __('تم المراجعه؟') }}</th>
                                    <th>{{ __('تم التدقيق ؟') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tasks as $task)
                                    @if(!$task->reviewed_at || !$task->audited_at)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>{{ $task->reviewed_at ? $task->reviewed_at :'--' }}</td>
                                            <td>{{ $task->audited_at ? $task->audited_at : ' --' }}</td>
                                            <td class="text-center">
                                                @if($task->reviewed_at)
                                                    <i class="fa fa-check-circle text-success"></i> <br>
                                                    {{ optional($task->reviewer)->name  }}
                                                @else
                                                    <i class="fa fa-times text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($task->audited_at)
                                                    <i class="fa fa-check-circle text-success"></i> <br>
                                                    {{ optional($task->auditor)->name  }}
                                                @else
                                                    <i class="fa fa-times text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="tile">
                        <h3 class="tile-title">المهام المنجزة</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>{{ __('المهمه') }}</th>
                                    <th>{{ __('تاريخ المراجعه') }}</th>
                                    <th>{{ __('تاريخ التدقيق') }}</th>
                                    <th>{{ __('المراجعه بواسطة') }}</th>
                                    <th>{{ __('التدقيق بواسطة') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($tasks as $task)
                                    @if($task->reviewed_at && $task->audited_at)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td class="text-center"><i class="fa fa-check-circle text-success"></i> {{$task->reviewed_at }}</td>
                                            <td class="text-center"><i class="fa fa-check-circle text-success"></i> {{$task->audited_at }}</td>
                                            <td class="text-center">
                                                {{ optional($task->reviewer)->name  }}
                                            </td>

                                            <td class="text-center">
                                                {{ optional($task->auditor)->name  }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    @endif
            </div>
        </main>
    @endsection
@endif
