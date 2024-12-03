@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file mr-2"></i>{{__('Worker Performance')}}</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-file fa-lg"></i></li>
                <li class="breadcrumb-item active" >{{__('Worker Performance')}}</li>
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
                                <th>{{__('Worker')}}</th>
                                <th>{{__('Feedbacks Count')}}</th>
                                <th>{{__('Reservation Count')}}</th>
                                <th>{{__('Average Feedback Rating')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reportData as $data)
                                <tr>
                                    <td>{{$data['Worker Name']}}</td>
                                    <td>{{$data['Feedback Count']}}</td>
                                    <td>{{$data['Reservation Count']}}</td>
                                    <td>{{round($data['Average Feedback Rating'], 1)}} <i class="fa fa-star text-warning"></i></td>
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


