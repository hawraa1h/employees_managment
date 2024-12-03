@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file mr-2"></i>{{__('Reservation Summary')}}</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-file fa-lg"></i></li>
                <li class="breadcrumb-item active" >{{__('Reservation Summary')}}</li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                     <h4>{{__('Filter Report')}}</h4>
                    <form class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">{{__('Select Customer')}}</label>
                            <select class="form-control" name="customer_id">
                                <option value="">{{__('Select Customer')}}</option>
                                @foreach(\App\Models\Customer::all() as $customer)
                                    <option value="{{$customer->cus_ID}}" {{request()->customer_id == $customer->cus_ID ? 'selected' : ''}}>{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">{{__('Select Worker')}}</label>
                            <select  class="form-control" name="worker_id">
                                <option value="">{{__('Select Worker')}}</option>
                                @foreach(\App\Models\Worker::all() as $worker)
                                    <option value="{{$worker->worker_ID}}" {{request()->worker_id == $worker->worker_ID ? 'selected' : ''}}>{{$worker->full_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">{{__('Date From')}}</label>
                            <input type="date" class="form-control" name="date_from" value="{{request()->date_from}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">{{__('Date To')}}</label>
                            <input type="date" class="form-control" name="date_to" value="{{request()->date_to}}">
                        </div>
                        <div class="form-group col-md-6 align-self-end">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-search"></i>{{__('Search')}}</button>
                            <a class="btn btn-danger text-white" href="{{route('main_admin.reports.reservation_summary')}}"><i class="fa fa-fw fa-lg fa-times-circle"></i>{{__('Clear Search')}}</a>
                        </div>
                    </form>
                </div>
            </div>
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
                                <th>{{__('Reservation ID')}}</th>
                                <th>{{__('Customer')}}</th>
                                <th>{{__('Worker')}}</th>
                                <th>{{__('Reservation Status')}}</th>
                                <th>{{__('Reservation Date')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reportData as $data)
                                <tr>
                                    <td>{{$data['res_ID']}}</td>
                                    <td>{{$data['customer']}}</td>
                                    <td>{{$data['worker']}}</td>
                                    <td>{{$data['res_Status']}}</td>
                                    <td>{{$data['date']}}</td>
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


