@extends('admin')

@section('content')
    
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