@extends('layouts.app')

@section('content')
	<div class="container py-4">
        <div class="col-12 mx-0 mt-3 shadow-sm p-3">
			<div class="row flex-lg-nowrap justify-content-between">
                
               <div class="card col-12 col-md-5 col-lg-3 d-flex justify-content-center pt-3 m-1">
                   <div class="d-flex align-items-center ">
                    <div class="col-6">
                        <svg class="svg-icon svg-danger" width="50" height="52" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                        </svg>
                       </div>
                       <div class="col-6 ">
                            <p>{{$users}}</p>
                            <p>Total Driver</p>
                       </div>
                   </div>
               </div>

               <div class="card col-12 col-md-5 col-lg-3 d-flex justify-content-center pt-3 m-1">
                <div class="d-flex align-items-center ">
                 <div class="col-6 col-md-4">
                    <svg class="svg-icon svg-danger" width="50" height="52" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                        <line x1="19" y1="7" x2="19" y2="10"></line>
                        <line x1="19" y1="14" x2="19" y2="14.01"></line>
                    </svg>
                    </div>
                    <div class="col-6 col-md-8 ">
                         <p>{{$pendingdriver}}</p>
                         <p>Waiting For Approval</p>
                    </div>
                </div>
            </div>

            <div class="card col-12 col-md-5 col-lg-3 d-flex justify-content-center pt-3 m-1">
                <div class="d-flex align-items-center ">
                 <div class="col-6">
                    <svg class="svg-icon svg-danger" width="50" height="52" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="7" cy="17" r="2"></circle>
                        <circle cx="17" cy="17" r="2"></circle>
                        <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5"></path>
                    </svg>
                    </div>
                    <div class="col-6 ">
                         <p>{{ $trip }}</p>
                         <p>Total Trips</p>
                    </div>
                </div>
            </div>

            <div class="card col-12 col-md-5 col-lg-3 d-flex justify-content-center pt-3 m-1">
                <div class="d-flex align-items-center ">
                 <div class="col-6">
                    <svg class="svg-icon svg-danger" width="50" height="52" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path>
                        <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
                    </svg>
                    </div>
                    <div class="col-6 ">
                         <p>${{$totalprice}}</p>
                         <p>Total Income</p>
                    </div>
                </div>
            </div>


            </div>
        </div>
		<div class="col-md-12 mx-0 mt-3 shadow-sm p-3">
			<div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="d-flex justify-content-between px-5">
                            <label for="topup">Topup Summary</label>
                            <div class="">
                                <button class="btn btn-sm btn-secondary" id="topup_day" onclick="topupChart('day')">D</button>
                                <button class="btn btn-sm btn-secondary" id="topup_week" onclick="topupChart('week')">W</button>
                                <button class="btn btn-sm btn-secondary" id="topup_month" onclick="topupChart('month')">M</button>
                                <button class="btn btn-sm btn-secondary" id="topup_year" onclick="topupChart('year')">Y</button>
                            </div>
                        </div>
                        <div style="width: 100%;"><canvas id="topup"></canvas></div>
                    </div>
			    </div>
                <div class="col-md-5">
                   <div class="card col-12 " >
                      <div class="card-header">
                          <p>Driver Rating and Count</p>
                      </div>
                      <div class="card-body">
                        <ul id="drivercount" class="navbar-nav" style="height: 300px;overflow-y:auto;overflow-x:hidden">
                        
                          
                        </ul>
    
                      </div>
                   </div>
                </div>
                {{-- <div class="col-md-3 text-center text-md-start">
                    <div class="">
                        <div class="mt-5">
                            <h1 class=" fw-bold">{{ $balence }} Ks</h1>
                            <p class="fw-bold text-muted">Income in this month</p>
                        </div>
                        <div class="">
                            <a class="btn btn-outline-dark py-1 fw-bold" href="{{ route('income.summary', 'day') }}"
                                style="border:1px solid #bbb; font-size:0.8rem;">All Income
                                Summary
                                <span class="ms-2 text-muted"><i class="fa-solid fa-chevron-right"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <div class="mt-4">
                            <h2>{{ $users->where('status', 'pending')->count() }}</h2>
                            <p class="fw-bold text-muted small">Pending {{ $users->where('status', 'pending')->count() }} Drivers</p>
                        </div>
                        <div class="">
                            <a class="btn btn-outline-dark py-1 fw-bold" href="{{ route('users.pending') }}"
                                style="border:1px solid #bbb; font-size:0.8rem;">Pending
                                <span class="ms-2 text-muted"><i class="fa-solid fa-chevron-right"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <div class="mt-4">
                            <h2>{{ $users->where('status', 'active')->count() }}</h2>
                            <p class="fw-bold text-muted small">Active {{ $users->where('status', 'active')->count() }} Drivers</p>
                        </div>
                        <div class="">
                            <a class="btn btn-outline-dark py-1 fw-bold" href="{{ route('users.active') }}"
                                style="border:1px solid #bbb; font-size:0.8rem;">Active
                                <span class="ms-2 text-muted"><i class="fa-solid fa-chevron-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div> --}}
            </div>
		</div>
        <div class="col-md-12 mx-0 mt-3 shadow-sm p-3">
                <div class="row mt-3 justify-content-between">
                    <div class="col-md-6 p-3">
                        <div class="d-flex justify-content-between px-5">
                            <label for="commission">Commission Fee Summmary</label>
                            <div class="">
                                <button class="btn btn-sm btn-secondary" id="comission_day" onclick="commissionChart('day')">D</button>
                                <button class="btn btn-sm btn-secondary" id="comission_week" onclick="commissionChart('week')">W</button>
                                <button class="btn btn-sm btn-secondary" id="comission_month" onclick="commissionChart('month')">M</button>
                                <button class="btn btn-sm btn-secondary" id="comission_year" onclick="commissionChart('year')">Y</button>
                            </div>
                        </div>
                        <div style="width: 100%;"><canvas id="commission"></canvas></div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="d-flex justify-content-between px-5">
                            <label for="topup">Trips Summary</label>
                            <div class="">
                                <button class="btn btn-sm btn-secondary" id="trip_day" onclick="tripChart('day')">D</button>
                                <button class="btn btn-sm btn-secondary" id="trip_week" onclick="tripChart('week')">W</button>
                                <button class="btn btn-sm btn-secondary" id="trip_month" onclick="tripChart('month')">M</button>
                                <button class="btn btn-sm btn-secondary" id="trip_year" onclick="tripChart('year')">Y</button>
                            </div>
                        </div>
                        <div style="width: 100%;"><canvas id="trip"></canvas></div>
                    </div>
                </div>
        </div>
	</div>
@endsection
@push('script')
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
	<script>
        const domain = window.location.href;
        let commissionChartInstance = null;
        let topupChartInstance = null;
        let tripChartInstance = null;



        async function topupChart(range) {
            await axios.get(`${domain}topup-chart/${range}`)
                .then((response) => {
                    const data = response.data;

                    let arr = Object.keys(data).map((key) => {
                        return {
                            date: data[key].date,
                            income: data[key].income,
                        };
                    });

                    if (!topupChartInstance) {
                        // Create the chart instance if it doesn't exist
                        const chartElement = document.getElementById("topup");
                        topupChartInstance = new Chart(chartElement, {
                            type: 'line',
                            data: {
                                labels: arr.map((row) => row.date),
                                datasets: [
                                    {
                                        label: `Topup per ${range}`,
                                        data: arr.map((row) => row.income),
                                        borderColor: '#ffc107',
                                        backgroundColor: '#ffc107',
                                        tension: 0.2,
                                    },
                                ],
                            },
                        });
                    } else {
                        // Update the existing chart instance with new data
                        topupChartInstance.data.labels = arr.map((row) => row.date);
                        topupChartInstance.data.datasets[0].label = `Topup per ${range}`;
                        topupChartInstance.data.datasets[0].data = arr.map((row) => row.income);

                        topupChartInstance.update();
                    }


                })
            .catch((error) => {
                    console.log(error);
                });
            const ranges = ['day', 'week', 'month', 'year'];
                    ranges.forEach((rag) => {
                        document.querySelector(`#topup_${rag}`).classList.remove('btn-dark');
                        document.querySelector(`#topup_${rag}`).classList.add('btn-secondary');
                    });

                    document.querySelector(`#topup_${range}`).classList.add('btn-dark');
                    document.querySelector(`#topup_${range}`).classList.remove('btn-secondary');
        }

        async function commissionChart(range) {
            await axios.get(`${domain}commission-chart/${range}`)
                .then((response) => {
                    const data = response.data;

                    let arr = Object.keys(data).map((key) => {
                        return {
                            date: data[key].date,
                            income: data[key].income,
                        };
                    });
                    if (!commissionChartInstance) {
                        // Create the chart instance if it doesn't exist
                        const chartElement = document.getElementById("commission");
                        commissionChartInstance = new Chart(chartElement, {
                            type: 'line',
                            data: {
                                labels: arr.map((row) => row.date),
                                datasets: [
                                    {
                                        label: `Commission per ${range}`,
                                        data: arr.map((row) => row.income),
                                        borderColor: '#9CFF2E',
                                        backgroundColor: '#38E54D',
                                        tension: 0.2,
                                    },
                                ],
                            },
                        });
                    } else {
                        // Update the existing chart instance with new data
                        commissionChartInstance.data.labels = arr.map((row) => row.date);
                        commissionChartInstance.data.datasets[0].label = `Commission per ${range}`;
                        commissionChartInstance.data.datasets[0].data = arr.map((row) => row.income);

                        commissionChartInstance.update();
                    }

                })
                .catch((error) => {
                    console.log(error);
                });

            const ranges = ['day', 'week', 'month', 'year'];
                    ranges.forEach((rag) => {
                        document.querySelector(`#comission_${rag}`).classList.remove('btn-dark');
                        document.querySelector(`#comission_${rag}`).classList.add('btn-secondary');
                    });

                    document.querySelector(`#comission_${range}`).classList.add('btn-dark');
                    document.querySelector(`#comission_${range}`).classList.remove('btn-secondary');
        }

        async function tripChart(range) {
            await axios.get(`${domain}trip-chart/${range}`)
                .then((response) => {
                    const data = response.data;
                    let arr = Object.keys(data).map((key) => {
                        return {
                            date: data[key].date,
                            tripCount: data[key].tripCount,
                        };
                    });
                    // console.log(arr);

                    if (!tripChartInstance) {
                        // Create the chart instance if it doesn't exist
                        const chartElement = document.getElementById("trip");
                        tripChartInstance = new Chart(chartElement, {
                            type: 'line',
                            data: {
                                labels: arr.map((row) => row.date),
                                datasets: [
                                    {
                                        label: `Trip per ${range}`,
                                        data: arr.map((row) => row.tripCount),
                                        borderColor: '#00C4FF',
                                        backgroundColor: '#30A2FF',
                                        tension: 0.2,
                                    },
                                ],
                            },
                        });
                    } else {
                        // Update the existing chart instance with new data
                        tripChartInstance.data.labels = arr.map((row) => row.date);
                        tripChartInstance.data.datasets[0].data = `Trip per ${range}`;
                        tripChartInstance.data.datasets[0].data = arr.map((row) => row.tripCount);
                        tripChartInstance.update();
                    }


                })
            .catch((error) => {
                    console.log(error);
                });

            const ranges = ['day', 'week', 'month', 'year'];
                    ranges.forEach((rag) => {
                        document.querySelector(`#trip_${rag}`).classList.remove('btn-dark');
                        document.querySelector(`#trip_${rag}`).classList.add('btn-secondary');
                    });

                    document.querySelector(`#trip_${range}`).classList.add('btn-dark');
                    document.querySelector(`#trip_${range}`).classList.remove('btn-secondary');
        }

        async function driverTripCount(range) {
            await axios.get(`${domain}trip-count-driver/${range}`)
                    .then((resporn) => {
                        let drivercount = document.querySelector('#drivercount');

                        // console.log(drivercount)
                        drivercount.innerHTML ='';
                     
                            resporn.data.forEach((item,idx)=>{
                                drivercount.innerHTML += `
                            <li title="Driver Id : ${item.driver_id}" class="nav-item d-flex  align-items-center border-bottom pb-2 my-2">
                                    <span class="col-1">${idx +1}</span>
                                    <div class="d-flex col-7">
                                        
                                        <img class="" src="${item.user_image.profile_image === null ? domain+'assets/logo/user.png': 'uploads/images/profiles/'+item.user_image.profile_image}" alt="User"
                                        style="width:3rem;height:3rem; object-fit: cover; object-position: center;border-radius:100%;margin-right:15px">
                            
        
                                        <div class="col-8 text-center">
                                            <div>${item.name}</div>
                                            <div>${item.phone}</div>
                                        </div>
                                    </div>
                                    <div class="col-5 text-center">
                                        <div>total trip</div>
                                        <div>${item.trips_count}</div>
                                    </div>
        
                                </li>
                            
                            `;
                            //console.log(item)
                            
                            })

                       
                        
                                                
                        // console.log(resporn.data)
                        
                    })
                    .catch((error) => {
                        // console.log(error)
                        drivercount.innerHTML = `
                            <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                            `;
                        
                    })
                




            const ranges = ['day', 'week', 'month', 'year'];
                    ranges.forEach((rag) => {
                        document.querySelector(`#trip_${rag}`).classList.remove('btn-dark');
                        document.querySelector(`#trip_${rag}`).classList.add('btn-secondary');
                    });

                    document.querySelector(`#trip_${range}`).classList.add('btn-dark');
                    document.querySelector(`#trip_${range}`).classList.remove('btn-secondary');
        }

      //start pusher driver trip count  
      Pusher.logToConsole = true;

            var pusher = new Pusher('ff6d2dc3e07b1864a77d', {
            cluster: 'ap1'
            });

            var channel = pusher.subscribe('driver-list-channel');
            channel.bind('driver-list-event', function(data) {
            //   alert(JSON.stringify(data));\

                    let booking = JSON.stringify(data)
            console.log(booking);
            });

   
     //   ebd pusher driver trip count 
         window.onload = function () {
            commissionChart('month');
            topupChart('month');
            tripChart('month');
            driverTripCount('month')
        };
    </script>
@endpush
