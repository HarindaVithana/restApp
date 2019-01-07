<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Vendor;
use App\BillPaid;
use Log;
use DB;
use Carbon\Carbon;
use DateTime;
use App\IssueFoodItem;
use App\FoodItemQuantity;
use App\Dish;
Use App\PayVendor;
use DatePeriod;
use DateInterval;


class DashboardController extends Controller
{
        public function __construct()
        {
            $this->middleware('auth');
        }  

    
    public function adminIndex(){

        $firstdate = BillPaid::orderBy('created_at', 'desc')->first()->created_at;  //get the last date of the bill
        $firstdate = explode(" ",$firstdate)[0];
        $date = $firstdate;
        $pricearray = array();
        $datearray = array();

        for ($x = 0; $x < 7; $x++) {      
            $netIncome =0;      
            $prices = BillPaid::where('created_at','like','%'.$date.'%')->select('price')->get();
            foreach($prices as $price){
                $netIncome = $price->price + $netIncome;
            }
            array_push($pricearray,$netIncome);   //append net income to price array
            array_push($datearray,$date);       
            $date = date('Y-m-d', strtotime($date . ' -1 days'));    //deduct day by day            
        } 

        $datearray = array_reverse($datearray);
        $pricearray = array_reverse($pricearray);                
        $billdates = BillPaid::select(DB::raw('DATE(created_at) as day'))->groupBy('day')->orderBy('day', 'desc')->get();
        $daytable = BillPaid::where('created_at','like','%'.$firstdate.'%')->get();     
        $employee =  Employee::all();
        $vendor = Vendor::all();


        $firstweek = BillPaid::select('created_at')->orderBy('created_at')->first()->created_at;
        $lastweek = BillPaid::select('created_at')->orderBy('created_at','desc')->first()->created_at;
        Log::info($firstweek);
        Log::info($lastweek);

        
        $firstweek= $firstweek->toDateString();
        $lastweek=$lastweek->toDateString();
        $date1 = new DateTime($firstweek);
        $date2 = new DateTime($lastweek);
        $interval = $date1->diff($date2);

        $weeks = floor(($interval->days) / 7);
        $weeksbetween =array();
        for($i = 1; $i <= $weeks; $i++){    
            
            $week = $date1->format("W");
            $date1->add(new DateInterval('P4D'));
            $week." = ".$firstweek." - ".$date1->format('Y-m-d')."<br/>";
           
            $date1->add(new DateInterval('P3D'));
            $darray = array(
                'start'=> $firstweek,
                'end' => $date1->format('Y-m-d')
            );
            $firstweek = $date1->format('Y-m-d');
            array_push($weeksbetween,$darray);
        }
        //Log::info($weeksbetween);


        //$weeks = BillPaid::select(DB::raw('WEEK(created_at) as week,created_at'))->groupBy('week')->orderBy('week', 'desc')->get();  //this will get us the week numbers from start to end of data
          //Log::info($weeks); 
        $results = BillPaid::select('created_at')
        ->orderBy('created_at','desc')
        ->get()
        ->groupBy(function (BillPaid $item) {
            return $item->created_at->format('Y-m');
        });
        //Log::info($results->keys());    //this will get the year and month 2019-01
        $months = $results->keys();
        //$date = Carbon::now(); 
        //$weekarray = array();     //this array includes start date and end date of each weeks. 
        
        // foreach($weeks as $week){            
        //     //Log::info($week->week);
        //     $weekNumber = $week->week;
        //     $year = $week->created_at;
        //     $year = explode(' ',$year)[0];
        //     $year = explode('-',$year)[0];
        //     $date->setISODate($year,($weekNumber+1)); //when given a week number it returns start date of that week
        //     $start = $date->startOfWeek()->toDateTimeString();    //get the start date of the week
        //     $end =  $date->endOfWeek()->addDays(1)->toDateTimeString();      // get the end date of the week
        //     $weekly = array(
        //         "start" => explode(" ",$start)[0],
        //         "end" => explode(" ",$end)[0]
        //     );           
        //     array_push($weekarray,$weekly);
        // }
        $montharray = array();     //this array includes start date and end date of each month. 
        
        $monthswithyears = array();
        foreach($months as $month){             
            array_push($monthswithyears,$month);
            $firstday = $month.'-01';          //first date of the month
            $lastday = date("Y-m-t", strtotime($firstday));            //last date of the month
               
            $monthly = array(
                "start" => $firstday,
                "end" => $lastday
            );           
            array_push($montharray,$monthly);
        }
       // $monthswithyears = str_replace('"', '', json_encode($monthswithyears));
        //Log::info($monthswithyears);
        

        $today = Carbon::now();
        $one_week_ago = Carbon::now()->subWeeks(1);
        $weeklytable = BillPaid::selectRaw( 'dish_id , dish_name ,sum(price) as totalPrice , sum(quantity) as totalQuantity')
            ->whereBetween('created_at', [$one_week_ago, $today])
            ->groupBy('dish_id')->get();       

                 
        //Log::info($weeklytable);

        $data = array(
            'employee' => $employee,
            'vendor' => $vendor,
            'pricearray' => $pricearray,
            'datearray' => $datearray,
            'daytable' => $daytable,
            'billdates' => $billdates,
            'weeklytable' => $weeklytable,
            'weeks' => $weeksbetween,
            'months' => $montharray,
            'monthsyears' => $monthswithyears
        );
        return View ('admin.adminDash')->with($data);
    }
   
    //for day table admin dash
    public function daytable(Request $request){
        $selectedDate = $request->get('date');      
        $daytable = BillPaid::where('created_at','like','%'.$selectedDate.'%')->get();
        $output ='<table id="daytable" class="table table-striped table-responsive-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
            <th class="th-lg">ID
            </th>
            <th class="th-lg">Food Item Name
            </th>                           
            <th class="th-lg">Quantity
            </th>
            <th class="th-lg">Price
            </th>    
            <th class="th-lg">Time
            </th>                  
            </tr>
        </thead>   
        <tbody >';
        foreach($daytable as $day){
            $output .= '<tr>
            <td>'. $day->id.' </td>
            <td>'. $day->dish_name.' </td>                                
            <td>'. $day->quantity.' </td>
            <td>'. $day->price.' </td>    
            <td>'. explode(" ",$day->created_at)[1].' </td>                        
        </tr>
        <input type="hidden" id="date" value="'.$selectedDate.'">';
        }
        $output .= '</tbody>
        <tfoot>
            <tr>
            <th>ID
            </th>
            <th>Food Item Name
            </th>                           
            <th>Quantity
            </th>
            <th>Price
            </th>   
            <th>Time
            </th>                      
            </tr>
        </tfoot>
        </table>
        <a class="btn btn-sm btn-primary" onclick="print(\'daytable\')">Get Report</a>';
        echo $output; 
            
    }
    //for weekly table admin dash
    public function weektable(Request $request){
        $startday = $request->get('start');
        $endday = $request->get('end');   
        Log::info($startday);
        Log::info( $endday);   
        $weeklytable = BillPaid::selectRaw( 'dish_id , dish_name ,sum(price) as totalPrice , sum(quantity) as totalQuantity')
            ->whereBetween('created_at', [$startday, $endday])
            ->groupBy('dish_id')->get();   
        Log::info($weeklytable);            
        $output = '<table id="weeklytable" class="table table-striped table-responsive-sm" cellspacing="0" width="100%">
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
        <tbody> ';  
        
        $total =0;
        foreach($weeklytable as $week){
            $output .= '<tr>
            <td>'. $week->dish_id.' </td>
            <td>'. $week->dish_name.' </td>                                
            <td>'. $week->totalQuantity.' </td>
            <td>'. $week->totalPrice.' </td>                              
        </tr>
       
        
        <input type="hidden" id="startdate" value="'.$startday.'">
        <input type="hidden" id="enddate" value="'.$endday.'">';
        $total += $week->totalPrice;
        }     
        $output .= '</tbody>
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
        </tabel>
        <h2>Total income - Rs.'.$total.'</h2>
        <a class="btn btn-sm btn-primary" onclick="print(\'weektable\')">Get Report</a>';              
        return response()->json([
            'output'=> $output,
            'weeklytable' => $weeklytable
        ]);  
    }


    public function inventoryIndex(){
        $today = Carbon::now()->toDateString();        
        $issuetable = IssueFoodItem::where('created_at','like','%'.$today.'%')->get();  //for today issued table
        $allissue = IssueFoodItem::all();   //for all issued table

        $itemNames = FoodItemQuantity::select('itemName')->groupBy('itemName')->get();

        $data = array(
            'issuetable' => $issuetable,
            'allissue' => $allissue,
            'itemNames' => $itemNames
        );
        return view ('inventory.inventoryDash')->with($data);
    }

    //inventory
    public function linegraph(Request $request){
        $selected  = $request->get('selected');
        Log::info($selected);
        $weekly = FoodItemQuantity::where('itemName','like','%'.$selected.'%')->get();
        Log::info($weekly);
        //$linearray = array();
        $quantityarray = array();
        $datearray = array();
        foreach ($weekly as $week){
            Log::info($week);
            $quanti = 
            array_push($quantityarray, $week->quantity);
            array_push( $datearray, explode(" ", $week->created_at)[0]);           
        }
        Log::info($quantityarray);
        return response()->json(array('quantity'=>$quantityarray,'dates'=>$datearray));
    }














//---------------------------------------------------------
    public function accountIndex(){
        $dish = Dish::all();        
       
        $firstweek = BillPaid::select('created_at')->orderBy('created_at')->first()->created_at;
        $lastweek = BillPaid::select('created_at')->orderBy('created_at','desc')->first()->created_at;
        Log::info($firstweek);
        Log::info($lastweek);

        
        $firstweek= $firstweek->toDateString();
        $lastweek=$lastweek->toDateString();
        $date1 = new DateTime($firstweek);
        $date2 = new DateTime($lastweek);
        $interval = $date1->diff($date2);

        $weeks = floor(($interval->days) / 7);
        $weeksbetween =array();
        for($i = 1; $i <= $weeks; $i++){    
            
            $week = $date1->format("W");
            $date1->add(new DateInterval('P4D'));
            $week." = ".$firstweek." - ".$date1->format('Y-m-d')."<br/>";
           
            $date1->add(new DateInterval('P3D'));
            $darray = array(
                'start'=> $firstweek,
                'end' => $date1->format('Y-m-d')
            );
            $firstweek = $date1->format('Y-m-d');
            array_push($weeksbetween,$darray);
        }



        $firstweek1 = PayVendor::select('created_at')->orderBy('created_at')->first()->created_at;
        $lastweek1 = PayVendor::select('created_at')->orderBy('created_at','desc')->first()->created_at;

        $firstweek1= $firstweek1->toDateString();
        $lastweek1=$lastweek1->toDateString();
        $date1e = new DateTime($firstweek1);
        $date2e = new DateTime($lastweek1);
        $interval1 = $date1e->diff($date2e);

        $weeks1 = floor(($interval1->days) / 7);
        $weeksbetween1 =array();
        for($i = 1; $i <= $weeks1; $i++){    
            
            $week = $date1e->format("W");
            $date1e->add(new DateInterval('P4D'));
            $week." = ".$firstweek1." - ".$date1e->format('Y-m-d')."<br/>";
           
            $date1e->add(new DateInterval('P3D'));
            $darray = array(
                'start'=> $firstweek1,
                'end' => $date1e->format('Y-m-d')
            );
            $firstweek1 = $date1e->format('Y-m-d');
            array_push($weeksbetween1,$darray);
        }








        $results = BillPaid::select('created_at')
        ->orderBy('created_at','desc')
        ->get()
        ->groupBy(function (BillPaid $item) {
            return $item->created_at->format('Y-m');
        });
        //Log::info($results->keys());    //this will get the year and month 2019-01
        $months = $results->keys();
        $montharray = array();     //this array includes start date and end date of each month. 
        
        $monthswithyears = array();
        foreach($months as $month){             
            array_push($monthswithyears,$month);
            $firstday = $month.'-01';          //first date of the month
            $lastday = date("Y-m-t", strtotime($firstday));            //last date of the month
               
            $monthly = array(
                "start" => $firstday,
                "end" => $lastday
            );           
            array_push($montharray,$monthly);
        }



        $results1 = PayVendor::select('created_at')
        ->orderBy('created_at','desc')
        ->get()
        ->groupBy(function (PayVendor $item) {
            return $item->created_at->format('Y-m');
        });

        $months1 = $results1->keys();
        $montharray1 = array();     //this array includes start date and end date of each month. 
        
        $monthswithyears1 = array();
        foreach($months1 as $month){             
            array_push($monthswithyears1,$month);
            $firstday = $month.'-01';          //first date of the month
            $lastday = date("Y-m-t", strtotime($firstday));            //last date of the month
               
            $monthly = array(
                "start" => $firstday,
                "end" => $lastday
            );           
            array_push($montharray1,$monthly);
        }
        //Log::info($weekarray);
        $today = Carbon::now();
        $one_week_ago = Carbon::now()->subWeeks(1);
        $weeklytable = BillPaid::selectRaw( 'dish_id , dish_name ,sum(price) as totalPrice , sum(quantity) as totalQuantity')
            ->whereBetween('created_at', [$one_week_ago, $today])
            ->groupBy('dish_id')->get();       

        $expensetbl = PayVendor::whereBetween('created_at', [$one_week_ago, $today])->get();         
        
        $unitpriceData =array();
        $noOfUnits =array();
        $netexpense =array();
        foreach($expensetbl as $ex){
            $hello= $ex->data;            
            $hello = explode(',',$hello);
            
            $res = preg_replace("/[^0-9.]/",'' , $hello);            
            //Log::info($res);
            array_push($unitpriceData,$res[0]);
            array_push($noOfUnits,$res[1]);
            array_push($netexpense,$res[2]);    
        }
        //Log::info($unitpriceData);
        
        
       
        $data = array(
            'weeks' => $weeksbetween,
            'weeks1' =>$weeksbetween1,
            'dishes'=>$dish,
            'weeklytable' => $weeklytable,
            'expensetable' => $expensetbl,
            'unitpriceData' => $unitpriceData,
            'noOfUnits' => $noOfUnits,
            'months' => $montharray,
            'months1' => $montharray1,
            'netexpense'=> $netexpense,
            'monthsyears' => $monthswithyears,
            'monthsyears1' => $monthswithyears1            
        );    
        return view('account.accountDash')->with($data);
    }

    //for price table account dash
    public function pricetable(Request $request){
        $startday = $request->get('start');
        $endday = $request->get('end');
        $weeklytable = BillPaid::selectRaw( 'dish_id , dish_name ,sum(price) as totalPrice , sum(quantity) as totalQuantity')
            ->whereBetween('created_at', [$startday, $endday])
            ->groupBy('dish_id')->get();  
        $output = '<table id="weeklytable" class="table table-striped table-responsive-sm" cellspacing="0" width="100%">
        <thead>
            <tr>
            <th class="th-lg">Dish ID
            </th>
            <th class="th-lg">Food Item Name
            </th>                           
            <th class="th-lg">Quantity
            </th>
            <th class="th-lg">Net Price
            </th>                                         
            </tr>
        </thead>
        <tbody> ';  
        foreach($weeklytable as $week){
            $output .= '<tr>
            <td>'. $week->dish_id.' </td>
            <td>'. $week->dish_name.' </td>                                
            <td>'. $week->totalQuantity.' </td>
            <td>'. $week->totalPrice.' </td>                              
        </tr>
        <input type="hidden" id="startdate" value="'.$startday.'">
        <input type="hidden" id="enddate" value="'.$endday.'">';
        }     
        $output .= '</tbody>
        <tfoot>
            <tr>
            <th>Dish ID
            </th>
            <th>Food Item Name
            </th>                           
            <th>Quantity
            </th>
            <th>Net Price
            </th>                                            
            </tr>
        </tfoot>
        </tabel>';
        echo $output;

    }

    public function expensetable(Request $request){
        $startday = $request->get('start');
        $endday = $request->get('end');   
        Log::info($startday);
        Log::info( $endday);   
        $expensetbl = PayVendor::whereBetween('created_at', [$startday, $endday])->get();          
        $unitpriceData =array();
        $noOfUnits =array();
        $netexpense =array();
        foreach($expensetbl as $ex){
            $hello= $ex->data;            
            $hello = explode(',',$hello);
            
            $res = preg_replace("/[^0-9.]/",'' , $hello);            
            //Log::info($res);
            array_push($unitpriceData,$res[0]);
            array_push($noOfUnits,$res[1]);
            array_push($netexpense,$res[2]);    
        }
        $output = '<table id="expensetable" class="table table-striped table-responsive-sm" cellspacing="0" width="100%">
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
        <tbody> ';  
        
        $total =0;
        foreach($expensetbl as $index => $expense){
            $output .= '<tr>
            <td>'. $expense->vendor_id.' </td>
            <td>'. $expense->vendor_name.' </td>                                
            <td>'. $expense->foodItem.' </td>
            <td>'. $unitpriceData[$index].' </td>  
            <td>'. $noOfUnits[$index].' </td>  
            <td>'. $netexpense[$index].' </td>                              
        </tr>
       
        
        <input type="hidden" id="startdate1" value="'.$startday.'">
        <input type="hidden" id="enddate1" value="'.$endday.'">';
        $total += $netexpense[$index];
        }     
        $output .= '</tbody>
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
        </tfoot>
        </tabel>
        <h2>Total expense - Rs.'.$total.'</h2> ';              
        return response()->json([
            'output'=> $output
            
        ]);  
    }
}
