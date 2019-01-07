@extends('accountant')
@section('content')

{{-- price List table --}}
<div class="card mb-5">        
    <div class="card-body">
        <h1 class="card-title mt-3">Price List</h1>
        <table id="pricetable" class="table table-striped table-responsive-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                <th class="th-lg">Dish ID
                </th>
                <th class="th-lg">Dish Name
                </th>
                <th class="th-lg">Unit Price
                </th>
                <th class="th-lg">Price Updated
                </th>                                     
                </tr>
            </thead>
            <tbody>
                @foreach($dishes as $dish)
                <tr>
                    <td> {{$dish->dish_id}} </td>
                    <td> {{$dish->dish_name}} </td>
                    <td> {{$dish->unit_price}} </td>
                    <td> {{$dish->updated_at}} </td>                                            
                </tr>
                @endforeach
            </tbody>            
        </table>
    </div>
</div>     


{{-- income table --}} 
{{-- weekly monthly summery --}}
<div class="card mb-5 fade_out" id="weekmonthsummery" >        
    <div class="card-body">
        <h1 class="card-title mt-3">Weekly /  Monthly Summery <span class="ml-3 mr-3" id="weekstartselect"></span> <span id="to" style="display:none">To</span> <span class="ml-3" id="weekendselect"></span></h1>
        <div class="row mt-5">
            <div class="col-sm-2.5 ml-4 mr-3" style="width:174px;">
                <div class="dropdown">        
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1-1"style="width:174px;" data-toggle="dropdown">Select Week</button>                   
                    <div class="dropdown-menu dropdown-primary" id="your-custom-id">
                        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                        @foreach($weeks as $week)
                            <a class="dropdown-item mdb-dropdownLink-1" id="selected" href="#" onclick="weeklytablemake('{{$week['start']}}','{{$week['end']}}')">{{$week['start']}} to {{$week['end']}} </a>
                        @endforeach                       
                    </div>
                </div>  
            </div>
            <div class="col-sm-2.5">
                <div class="dropdown">        
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1-1" data-toggle="dropdown">Select Month</button>                   
                    <div class="dropdown-menu dropdown-primary" id="your-custom-id">
                        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                        @foreach($months as $index => $month)
                    <a class="dropdown-item mdb-dropdownLink-1" id="selected" href="#" onclick="weeklytablemake('{{$month['start']}}','{{$month['end']}}')">{{json_encode($monthsyears[$index])}} </a>
                        @endforeach                       
                    </div>
                </div>  
            </div>
            <div class="col-sm-2">
                <div class="md-form ml-3">
                    <input placeholder="Selected date" type="text" id="strt_date" class="form-control datepicker">
                    <label for="date-picker-example">Start Date</label>
                  </div>
            </div>
            <div class="col-sm-2 ml-3">
                <div class="md-form">
                    <input placeholder="Selected date" type="text" id="end_date" class="form-control datepicker">
                    <label for="date-picker-example">End Date</label>
                  </div>
            </div>
            <div class="col-sm-2.5 ml-5" style="margin-top:-18px">
                <div class="md-form">
                    <a class="btn btn-primary" onclick="weeklytablemake(document.getElementById('strt_date').value,document.getElementById('end_date').value)">Custom Search</a>
                  </div>
            </div>
        </div>            
        
        <div id="weektabledata"></div> 
        <div class="tohide1">    
            <table id="weeklytable" class="table table-striped table-responsive-sm" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th class="th-lg">Dish ID
                    </th>
                    <th class="th-lg">Food Item Name
                    </th>                           
                    <th class="th-lg">Quantity
                    </th>
                    <th class="th-lg">Net Income (Rs)
                    </th>                                         
                    </tr>
                </thead>
                <tbody>{{ csrf_field() }}
                    <div style="display:none">
                    @php($total =0);  </div>
                    @foreach($weeklytable as $weekly)
                    <tr>
                        <td> {{$weekly->dish_id}} </td>
                        <td> {{$weekly->dish_name}} </td>
                        <td> {{$weekly->totalQuantity}} </td>
                        <td> {{$weekly->totalPrice}} </td>                                          
                    </tr>
                    <div style="display:none">
                    {{$total += $weekly->totalPrice}}</div>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                    <th>Dish ID
                    </th>
                    <th>Food Item Name
                    </th>                           
                    <th>Quantity
                    </th>
                    <th>Net Income (Rs)
                    </th>                                            
                    </tr>
                    
                </tfoot>
            </table>
            <h2>Total income - Rs.{{$total}}</h2>
            
        </div>            

    </div>
</div>    





{{-- expense table --}}    
<div class="card mb-5">        
    <div class="card-body">
        <h1 class="card-title mt-3">Expese Data <span class="ml-3 mr-3" id="weekstartselect1"></span> <span id="to1" style="display:none">To</span> <span class="ml-3" id="weekendselect1"></span></h1>
        <div class="row mt-5">
            <div class="col-sm-2.5 ml-4 mr-3" style="width:174px;">
                <div class="dropdown">        
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1-1"style="width:174px;" data-toggle="dropdown">Select Week</button>                   
                    <div class="dropdown-menu dropdown-primary" id="your-custom-id">
                        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                        @foreach($weeks1 as $week)
                            <a class="dropdown-item mdb-dropdownLink-1" id="selected" href="#" onclick=" expensetablemake('{{$week['start']}}','{{$week['end']}}')">{{$week['start']}} to {{$week['end']}} </a>
                        @endforeach                       
                    </div>
                </div>  
            </div>
            <div class="col-sm-2.5">
                <div class="dropdown">        
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1-1" data-toggle="dropdown">Select Month</button>                   
                    <div class="dropdown-menu dropdown-primary" id="your-custom-id">
                        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                        @foreach($months1 as $index => $month)
                    <a class="dropdown-item mdb-dropdownLink-1" id="selected" href="#" onclick=" expensetablemake('{{$month['start']}}','{{$month['end']}}')">{{json_encode($monthsyears1[$index])}} </a>
                        @endforeach                       
                    </div>
                </div>  
            </div>
            <div class="col-sm-2">
                <div class="md-form ml-3">
                    <input placeholder="Selected date" type="text" id="strt_date" class="form-control datepicker">
                    <label for="date-picker-example">Start Date</label>
                  </div>
            </div>
            <div class="col-sm-2 ml-3">
                <div class="md-form">
                    <input placeholder="Selected date" type="text" id="end_date" class="form-control datepicker">
                    <label for="date-picker-example">End Date</label>
                  </div>
            </div>
            <div class="col-sm-2.5 ml-5" style="margin-top:-18px">
                <div class="md-form">
                    <a class="btn btn-primary" onclick=" expensetablemake(document.getElementById('strt_date1').value,document.getElementById('end_date1').value)">Custom Search</a>
                  </div>
            </div>
        </div>        
        <div id="expensetabledata"></div> 
        <div class="tohide2">    
            <table id="expensetable" class="table table-striped table-responsive-sm" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th class="th-lg">Vendor ID
                    </th>
                    <th class="th-lg">Vendor Name
                    </th>                            
                    <th class="th-lg">Food Item
                    </th> 
                    <th class="th-lg">Unit Price
                    </th> 
                    <th class="th-lg"># Of Units
                    </th> 
                    <th class="th-lg">Total Expense
                    </th> 
                                                            
                    </tr>
                </thead>
                <tbody>{{ csrf_field() }}  
                    <div style="display:none">
                    @php($total1 =0);  </div>
                    @foreach($expensetable as $index => $expense)
                    <tr>
                        <td> {{$expense->vendor_id}} </td>
                        <td> {{$expense->vendor_name}} </td>                     
                        <td> {{$expense->foodItem}} </td>  
                        <td> {{json_encode($unitpriceData[$index],JSON_NUMERIC_CHECK)}} </td>  
                        <td> {{json_encode($noOfUnits[$index],JSON_NUMERIC_CHECK)}} </td>  
                        <td> {{json_encode($netexpense[$index],JSON_NUMERIC_CHECK)}} </td>                                                        
                    </tr>
                    <div style="display:none">
                    {{$total1 += json_encode($netexpense[$index],JSON_NUMERIC_CHECK)}}</div>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                    <th class="th-lg">Vendor ID
                    </th>
                    <th class="th-lg">Vendor Name
                    </th>                            
                    <th class="th-lg">Food Item
                    </th> 
                    <th class="th-lg">Unit Price
                    </th> 
                    <th class="th-lg"># Of Units
                    </th> 
                    <th class="th-lg">Total Expense
                    </th> 
                                                            
                    </tr>
                </tfoot>
            </table>
            <h2>Total income - Rs.{{$total1}}</h2>
        </div>            

    </div>
</div>     
 <script>
   $(document).ready(function () {
       // Data Picker Initialization
       $('.datepicker').pickadate({
            // Escape any “rule” characters with an exclamation mark (!).
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy/mm/dd',
            hiddenPrefix: 'prefix__',
            hiddenSuffix: '__suffix'
        });

        // pricetable
        $('#pricetable').dataTable({
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5
        });
        $('#pricetable_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#pricetable_wrapper .dataTables_filter').find('input').each(function () {
            $('input').attr("placeholder", "Search");
            $('input').removeClass('form-control-sm');
        });
        $('#pricetable_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#pricetable_wrapper .dataTables_filter').addClass('md-form');
        $('#pricetable_wrapper select').removeClass(
            'custom-select custom-select-sm form-control form-control-sm');
        $('#pricetable_wrapper select').addClass('mdb-select');
        $('#pricetable_wrapper .mdb-select').material_select();
        $('#pricetable_wrapper .dataTables_filter').find('label').remove();
//-------------------------------------------------------------------------------------------------------------------
       //weekly table
       $('#weeklytable').dataTable({
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5
        });
        $('#weeklytable_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#weeklytable_wrapper .dataTables_filter').find('input').each(function () {
            $('input').attr("placeholder", "Search");
            $('input').removeClass('form-control-sm');
        });
        $('#weeklytable_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#weeklytable_wrapper .dataTables_filter').addClass('md-form');
        $('#weeklytable_wrapper select').removeClass(
            'custom-select custom-select-sm form-control form-control-sm');
        $('#weeklytable_wrapper select').addClass('mdb-select');
        $('#weeklytable_wrapper .mdb-select').material_select();
        $('#weeklytable_wrapper .dataTables_filter').find('label').remove();
        
        //expense table
       $('#expensetable').dataTable({
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 5
        });
        $('#expensetable_wrapper').find('label').each(function () {
            $(this).parent().append($(this).children());
        });
        $('#expensetable_wrapper .dataTables_filter').find('input').each(function () {
            $('input').attr("placeholder", "Search");
            $('input').removeClass('form-control-sm');
        });
        $('#expensetable_wrapper .dataTables_length').addClass('d-flex flex-row');
        $('#expensetable_wrapper .dataTables_filter').addClass('md-form');
        $('#expensetable_wrapper select').removeClass(
            'custom-select custom-select-sm form-control form-control-sm');
        $('#expensetable_wrapper select').addClass('mdb-select');
        $('#expensetable_wrapper .mdb-select').material_select();
        $('#expensetable_wrapper .dataTables_filter').find('label').remove();

   }); 

   // weeklytable ajax
   function weeklytablemake(start, end){                                     
        console.log(start);
        console.log(end);
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url:"{{ route('DashboardController.weektable')}}",    
            method:"POST",
            data:{ start:start,end:end, _token:_token},           
            success:function(data){                  

                $('.tohide1').fadeOut();           
                $('#weektabledata').html(data.output); 
                weekstabledata = data.output;
                document.getElementById('to').style.display='inline';                 
                $('#weekstartselect').html(document.getElementById('startdate').value );
                $('#weekendselect').html(document.getElementById('enddate').value);

               //weekly table
                $('#weeklytable').dataTable({
                    "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
                    "iDisplayLength": 5
                });
                $('#weeklytable_wrapper').find('label').each(function () {
                    $(this).parent().append($(this).children());
                });
                $('#weeklytable_wrapper .dataTables_filter').find('input').each(function () {
                    $('input').attr("placeholder", "Search");
                    $('input').removeClass('form-control-sm');
                });
                $('#weeklytable_wrapper .dataTables_length').addClass('d-flex flex-row');
                $('#weeklytable_wrapper .dataTables_filter').addClass('md-form');
                $('#weeklytable_wrapper select').removeClass(
                    'custom-select custom-select-sm form-control form-control-sm');
                $('#weeklytable_wrapper select').addClass('mdb-select');
                $('#weeklytable_wrapper .mdb-select').material_select();
                $('#weeklytable_wrapper .dataTables_filter').find('label').remove();

            }           
        })
        
    }    




    // expensetable ajax
   function expensetablemake(start, end){                                     
        console.log(start);
        console.log(end);
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url:"{{ route('DashboardController.expensetable')}}",    
            method:"POST",
            data:{ start:start,end:end, _token:_token},           
            success:function(data){                  

                $('.tohide2').fadeOut();           
                $('#expensetabledata').html(data.output); 
                //weekstabledata = data.output;
                document.getElementById('to1').style.display='inline';                 
                $('#weekstartselect1').html(document.getElementById('startdate').value );
                $('#weekendselect1').html(document.getElementById('enddate').value);

               //expense table
                $('#expensetable').dataTable({
                    "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
                    "iDisplayLength": 5
                });
                $('#expensetable_wrapper').find('label').each(function () {
                    $(this).parent().append($(this).children());
                });
                $('#expensetable_wrapper .dataTables_filter').find('input').each(function () {
                    $('input').attr("placeholder", "Search");
                    $('input').removeClass('form-control-sm');
                });
                $('#expensetable_wrapper .dataTables_length').addClass('d-flex flex-row');
                $('#expensetable_wrapper .dataTables_filter').addClass('md-form');
                $('#expensetable_wrapper select').removeClass(
                    'custom-select custom-select-sm form-control form-control-sm');
                $('#expensetable_wrapper select').addClass('mdb-select');
                $('#expensetable_wrapper .mdb-select').material_select();
                $('#expensetable_wrapper .dataTables_filter').find('label').remove();

            }           
        })
        
    }    
   
 </script>     
@endsection