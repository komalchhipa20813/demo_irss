<!DOCTYPE html>

<html>
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="">
	<meta name="author" content="">
	<meta name="keywords" content="">

  <title>Carnival Event | RETINUE Insurance Management System</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <!-- End fonts -->

  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  <meta http-equiv="refresh" content="1200000">
  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/owl.carousel.min.css') }}" rel="stylesheet" />

  <!-- end plugin css -->
  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- end common css -->
  @stack('style')
</head>
<body>
  <div class="main-wrapper" id="app">
    <div class="owl-carousel owl-theme carouselWrap">
      <div class="item bg-08"><img src="{{ asset('images/1cr.jpg') }}" alt=""></div>
      <div class="item bg-01"><img src="{{ asset('images/CLAINT POP UP.jpg') }}" alt=""></div>
      <div class="item">
        <div class="CarnivalBox carnival-buis">
          <div class="CarnivalBoxLeft">
            <div class="CarnivalLogo"><img src="{{ asset('images/CarnivalLogo.png') }}" alt="" /></div>
            <img src="{{ asset('images/merryChristmas.png') }}" alt="" />
          </div> 
          <div class="CarnivalBoxMiddle">            
            <div class="CarnivalTable">
              <table class="table">
                <thead>
                  <tr>
                    <th>TOTAL BUSINESS</th>
                    <th>TOTAL NOP</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td id="total_amount"></td>
                    <td id="total_nop"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="CarnivalBoxRight"><img src="{{ asset('images/SANTA.png') }}" alt="" /></div>
        </div>
      </div>
      <div class="item bg-02"><img src="{{ asset('images/MALASIA.jpg') }}" alt=""></div>
      <div class="item bg-03"><img src="{{ asset('images/SINGAPORE-.jpg') }}" alt=""></div>
      <div class="item"> 
        <div class="CarnivalBox carnival-pol">
          <div class="CarnivalBoxLeft">
            <div class="CarnivalLogo"><img src="{{ asset('images/CarnivalLogo.png') }}" alt="" /></div>
            <img src="{{ asset('images/merryChristmas.png') }}" alt="" />
          </div>
          <div class="CarnivalBoxMiddle carnival-policy">            
            <div class="CarnivalTable">
              <table class="table">
                <thead>
                  <tr>
                    <th></th>
                    <th>PREMIUM</th>
                    <th>NOP</th>
                  </tr>
                </thead>
                <tbody class="policy_wise_div">
                  {{-- <tr><th>MOTOR</th><td></td><td>400</td></tr>
                  <tr><th>HEALTH</th><td></td><td>400</td></tr>
                  <tr><th>SME</th><td></td><td>400</td></tr> --}}
                  {{-- @foreach ($data->policy_wise as $key=>$item)
                  <tr><th>{{ $key }}</th><td>{{ $item->total }}</td><td>{{ $item->nop }}</td></tr>
                  @endforeach --}}
                </tbody>
              </table>
            </div>
          </div>
          <div class="CarnivalBoxRight"><img src="{{ asset('images/SANTA.png') }}" alt="" /></div>
        </div>
      </div>
      <div class="item bg-04"><img src="{{ asset('images/SINGAPORE MALAYSIA.jpg') }}" alt=""></div>
      <div class="item">
        <div class="CarnivalBox">
          <div class="CarnivalBoxLeft"><div class="CarnivalLogo"><img src="{{ asset('images/CarnivalLogo.png') }}" alt="" /></div><img src="{{ asset('images/merryChristmas.png') }}" alt="" /></div>
          <div class="CarnivalBoxMiddle">            
            <div class="CarnivalTable">
              <table class="table">
                <thead>
                  <tr>
                    <th>COMPANY NAME</th>
                    <th>PREMIUM</th>
                    <th>NOP</th>
                  </tr>
                </thead>
                <tbody class="company_wise_div">
                  <tr><td>Tata AIG General Insurance Company Limited</td><td>5419792</td><td>291</td></tr>
                  <tr><td>ICICI Lombard General Insurance Company Limited</td><td>1283087</td><td>174</td></tr>
                  <tr><td>HDFC Ergo General Insurance Company Limited</td><td>1239290</td><td>77</td></tr>
                  <tr><td>Reliance General Insurance Company Limited</td><td>1194924</td><td>74</td></tr>
                  <tr><td>The Oriental Insurance Company Limited</td><td>910564</td><td>52</td></tr>
                  <tr><td>Liberty General Insurance Limited</td><td>753141</td><td>143</td></tr>
                  <tr><td>Bajaj Allianz General Insurance Company Limited</td><td>705802</td><td>170</td></tr>
                  <tr><td>Royal Sundaram General Insurance Company Limited</td><td>589930</td><td>47</td></tr>
                  <tr><td>Future Generali India Insurance Company Limited</td><td>386825</td><td>111</td></tr>
                  <tr><td>ADITYA BIRLA HEALTH INSURANCE COMPANY LIMITED</td><td>322425</td><td>9</td></tr>
                  <tr><td>Magma HDI General Insurance Company Limited</td><td>270201</td><td>17</td></tr>
                  <tr><td>Star Health & Allied Insurance Company Limited</td><td>266637</td><td>13</td></tr>
                  <tr><td>The New India Assurance Company Limited</td><td>246838</td><td>24</td></tr>
                  <tr><td>Universal Sompo General Insurance Company Limited</td><td>218803</td><td>16</td></tr>
                  <tr><td>Kotak Mahindra General Insurance Company LImited</td><td>179867</td><td>15</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          {{-- <div class="CarnivalBoxRight"><img src="{{ asset('images/SANTA.png') }}" alt="" /></div> --}}
        </div>
      </div>
      <div class="item bg-05"><img src="{{ asset('images/MOTOR.jpg') }}" alt=""></div>
      <div class="item">
        <div class="CarnivalBox">
          <div class="CarnivalBoxLeft"><div class="CarnivalLogo"><img src="{{ asset('images/CarnivalLogo.png') }}" alt="" /></div><img src="{{ asset('images/merryChristmas.png') }}" alt="" /></div>
          <div class="CarnivalBoxMiddle">            
            <div class="topMotor"><p>TOP 10 OF MOTOR</p></div>
            <div class="CarnivalTable">
              <table class="table">
                <thead>
                  <tr>
                    <th>CODE</th>
                    <th>PREMIUM</th>
                    <th>NOP</th>
                  </tr>
                </thead>
                <tbody class="agent_motor_wise_div">
                  {{-- <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr> --}}
                  {{-- @foreach ($data->agent_wise->motor as $key=>$item)
                  <tr><td>{{ $item->code }}</td><td>{{ $item->total }}</td><td>{{ $item->nop }}</td></tr>
                  @endforeach --}}
                </tbody>
              </table>
            </div>
          </div>
          <div class="CarnivalBoxRight"><img src="{{ asset('images/SANTA.png') }}" alt="" /></div>
        </div>
      </div>
      <div class="item bg-06"><img src="{{ asset('images/HEALTH.jpg') }}" alt=""></div>
      <div class="item">
        <div class="CarnivalBox">
          <div class="CarnivalBoxLeft"><div class="CarnivalLogo"><img src="{{ asset('images/CarnivalLogo.png') }}" alt="" /></div><img src="{{ asset('images/merryChristmas.png') }}" alt="" /></div>
          <div class="CarnivalBoxMiddle">
            
            <div class="topMotor"><p>TOP 10 OF HEALTH</p></div>
            <div class="CarnivalTable">
              <table class="table">
                <thead>
                  <tr>
                    <th>CODE</th>
                    <th>PREMIUM</th>
                    <th>NOP</th>
                  </tr>
                </thead>
                <tbody class="agent_health_wise_div">
                  {{-- <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr> --}}
                  {{-- @foreach ($data->agent_wise->health as $key=>$item)
                  <tr><td>{{ $item->code }}</td><td>{{ $item->total }}</td><td>{{ $item->nop }}</td></tr>
                  @endforeach --}}
                </tbody>
              </table>
            </div>
          </div>
          <div class="CarnivalBoxRight"><img src="{{ asset('images/SANTA.png') }}" alt="" /></div>
        </div>
      </div>
      <div class="item bg-07"><img src="{{ asset('images/SME.jpg') }}" alt=""></div>
      <div class="item">
        <div class="CarnivalBox">
          <div class="CarnivalBoxLeft"><div class="CarnivalLogo"><img src="{{ asset('images/CarnivalLogo.png') }}" alt="" /></div><img src="{{ asset('images/merryChristmas.png') }}" alt="" /></div>
          <div class="CarnivalBoxMiddle">            
            <div class="topMotor"><p class="title-agent">TOP 10 OF SME</p></div>
            <div class="CarnivalTable">
              <table class="table">
                <thead>
                  <tr>
                    <th>CODE</th>
                    <th>PREMIUM</th>
                    <th>NOP</th>
                  </tr>
                </thead>
                <tbody class="agent_sme_wise_div">
                  {{-- <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr>
                  <tr><td>AA01A</td><td>50000</td><td>10</td></tr> --}}
                  {{-- @foreach ($data->agent_wise->sme as $key=>$item)
                  <tr><td>{{ $item->code }}</td><td>{{ $item->total }}</td><td>{{ $item->nop }}</td></tr>
                  @endforeach --}}
                </tbody>
              </table>
            </div>
          </div>
          <div class="CarnivalBoxRight"><img src="{{ asset('images/SANTA.png') }}" alt="" /></div>
        </div>
      </div>
      
    </div> 
  </div>

    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->
   <script type="text/javascript">
     var aurl = {!! json_encode(url('/')) !!}
     /* Ajax Set Up */
     $.ajaxSetup({
          headers: {
              "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
          },
      });
   </script>
   <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
   <script src="{{ asset('assets/js/carnival/carnival.js') }}"></script>
</body>
</html>
