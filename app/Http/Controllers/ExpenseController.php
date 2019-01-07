<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PayVendor;
use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Employee;
use App\Vendor;
use App\BillPaid;
use Log;
use DB;


use App\IssueFoodItem;
use App\FoodItemQuantity;
use App\Dish;

use DatePeriod;


class ExpenseController extends Controller
{
    //for expense table in admin

    public function expenseIndex(){
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
        return view('admin.expense')->with($data);
    }

}
