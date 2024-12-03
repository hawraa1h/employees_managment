@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file mr-2"></i>{{__('Worker Sales Profit')}}</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-file fa-lg"></i></li>
                <li class="breadcrumb-item active" >{{__('Worker Sales Profit')}}</li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <h4>{{__('Filter Report')}}</h4>
                    <form class="row">

                        <div class="form-group col-md-6">
                            <label class="control-label">{{__('Select Worker')}}</label>
                            <select  class="form-control" name="worker_id">
                                <option value="">{{__('Select Worker')}}</option>
                                @foreach(\App\Models\Worker::all() as $worker)
                                    <option value="{{$worker->worker_ID}}" {{request()->worker_id == $worker->worker_ID ? 'selected' : ''}}>{{$worker->full_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 align-self-end">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>{{__('Search')}}</button>
                            <a class="btn btn-danger text-white" href="{{route('main_admin.reports.sales_profit')}}"><i class="fa fa-fw fa-lg fa-times-circle"></i>{{__('Clear Search')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            @if($bestWorker)
            <div class="col-md-6">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-dollar fa-3x"></i>
                    <div class="info p-3">
                        <h4>{{__('Best Worker Has Profit')}}</h4>
                        <h6>{{$bestWorker['Worker_Name']}}</h6>
                        <p><b class="text-danger"> <strong>{{$bestWorker['Total_Profit']}} SAR</strong></b></p>
                    </div>
                </div>
            </div>
            @endif
            @if($worstWorker)
                <div class="col-md-6">
                    <div class="widget-small danger coloured-icon"><i class="icon fa fa-dollar fa-3x"></i>
                        <div class="info p-3">
                            <h4>{{__('Worst Worker Has Profit')}}</h4>
                            <h6>{{$worstWorker['Worker_Name']}}</h6>
                            <p><b class="text-danger"> <strong>{{$worstWorker['Total_Profit']}} {{__('SAR')}}</strong></b></p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">

                        <div class="row d-print-none mt-2 mb-4">
                            <div  class="col-12 text-right">
                                <a onclick="window.print()" class="btn btn-primary" href="javascript:"><i class="fa fa-print"></i> {{__('Print')}} </a></div>
                        </div>
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>{{__('Worker')}}</th>
                                <th>{{__('Total Sales')}}</th>
                                <th>{{__('Total Cost')}}</th>
                                <th>{{__('Total Profit')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reportData as $data)
                                <tr>
                                    <td>{{$data['Worker_Name']}} {{__('SAR')}}</td>
                                    <td>{{$data['Total_Sales']}} {{__('SAR')}}</td>
                                    <td>{{$data['Total_Cost']}} {{__('SAR')}}</td>
                                    <td>{{$data['Total_Profit']}} {{__('SAR')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


