@extends('admin/master')
@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('title') Dashboard @endsection
@section('content')

    
        <div class="row">

            <div class="col-lg-3 col-md-3 col-sm-6 mb-3 px-1">
                <div class="card dashboard text-white bg-warning o-hidden h-100 shadow shadow-lg">
                    <div class="card-body">
                        {{-- <div class="card-body-icon">
                            <i class="fa fa-fw fa-envelope"></i>
                        </div> --}}
                        <div class="mr-5">
                            <h3>{{ App\Models\User::count() }}</h3>
                            <h5 class="" style="color:black!important;">Total Users!</h5>
                        </div>
                    </div>
                    {{-- <a class="card-footer text-white clearfix small z-1" href="{{url('admin/inbox')}}">
                        <span class="float-left">View Messages</span>
                        <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </a> --}}
                </div>
            </div>


            <div class="col-lg-3 col-md-3 col-sm-6 mb-3 px-1">
                <div class="card dashboard text-white bg-secondary o-hidden h-100 shadow shadow-lg">
                    <div class="card-body">
                        {{-- <div class="card-body-icon">
                            <i class="fa fa-fw fa-envelope"></i>
                        </div> --}}
                        <div class="mr-5">
                            <h3>{{ $totalContest }}</h3>
                            <h5 class="">Total Essays!</h5>
                        </div>
                    </div>
                    {{-- <a class="card-footer text-white clearfix small z-1" href="{{url('admin/clicks')}}">
                        <span class="float-left">View Clicks</span>
                        <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </a> --}}
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 mb-3 px-1">
                <div class="card dashboard text-white bg-dark o-hidden h-100 shadow shadow-lg">
                    <div class="card-body">
                        {{-- <div class="card-body-icon">
                            <i class="fa fa-fw fa-envelope"></i>
                        </div> --}}
                        <div class="mr-5">
                            <h3 class="text-white">{{ $totalContestRating }}</h3>
                            <h5 class="">Total Ratings!</h5>
                        </div>
                    </div>
                    {{-- <a class="card-footer text-white clearfix small z-1" href="{{url('admin/clicks')}}">
                        <span class="float-left">View Clicks</span>
                        <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </a> --}}
                </div>
            </div>
          

            
            <div class="col-lg-3 col-md-3 col-sm-6 mb-3 px-1">
                <div class="card dashboard  border border-dark bg-info o-hidden h-100 shadow shadow-lg">
                    <div class="card-body">
                        {{-- <div class="card-body-icon">
                            <i class="fa fa-fw fa-envelope"></i>
                        </div> --}}
                        <div class="mr-5">
                            <h3>{{ $totalWebsites }}</h3>
                            <h5 style="color:black!important;">Total Websites!</h5>
                        </div>
                    </div>
                    {{-- <a class="card-footer text-white clearfix small z-1" href="{{url('admin/users')}}">
                        <span class="float-left">View Affiliaters</span>
                        <span class="float-right">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    </a> --}}
                </div>
            </div>


        </div>
        
        
      <!-- /cards -->
    <h2></h2>
    <div class="box_general padding_bottom">
        <div class="header_box version_2">
          <h2><i class="fa fa-bar-chart"></i>Reports(last 6 months)</h2>
        </div>
        <div class="col-lg-12" id="chart">
            <div style=" width:100%; height:400px" class="border shadow shadow-lg">
            <canvas id="myChart"></canvas>
        </div>
        </div>

    </div>



@endsection
@section('custom-js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["{{ date('M', strtotime('-5 months')) }}",
                            "{{ date('M', strtotime('-4 months')) }}",
                            "{{ date('M', strtotime('-3 months')) }}",
                            "{{ date('M', strtotime('-2 months')) }}",
                            "{{ date('M', strtotime('-1 months')) }}",
                            "{{ date('M') }}"
                        ],
                datasets: [
                    {
                    label: ['#Websites Clicks'],
                    data: @json($clicks),
                    backgroundColor:'purple',
                    borderColor:'black',
                    borderWidth: 2
                }
            ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                maintainAspectRatio: false,
            }
        });
    </script>

@endsection
