<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FoodItem;
use App\PayVendor;
use App\Notification;
use Log;

class PayVendorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    } 
    public function index(){
        $fooditems = FoodItem::all();
        return view('account.payvendor')->with('fooditem',$fooditems);
    }

    //No use in this function
    public function getitems(Request $request){
        $vendorId = $request->get('vendor_id');
        Log::info('hello');
        // $items = FoodItem::where('vendor_id','like','%'.$vendorId.'%')->get();
        // Log::info($items);
    }

    public function makeTable(Request $request){
        
        $items = $request->get('values');
        $output = '<table class="table table-sm">
        <thead>
          <tr>
            <th scope="col">Item ID</th>
            <th scope="col">Food Item</th>
            <th scope="col">Unit Price</th>
            <th scope="col">Amount/Units</th>
            <th scope="col">Price</th>
          </tr>
        </thead>
        <tbody>';
       
        foreach ($items as $item){            
            $itemId = FoodItem::where('itemName','like','%'.$item.'%')->value('id');          
            $unit = FoodItem::where('itemName','like','%'.$item.'%')->value('unit');    
            $output .= '<tr>
            <th scope="row">'.$itemId.'</th>
            <td>'.$item.'</td>
            <td><input type="text" class="auto-calc unit-price form-control" placeholder="From Rupees"></td>
            <td><input type="text" class="auto-calc amount form-control" placeholder="'.$unit.'" ></td>
            <td><input type="text" class="total-cost form-control" placeholder="From Rupees"></td>
          </tr>';
        
        }
        $output .= '</tbody>
                    </table><p>Total invoice amount:Rs. <span id="total-invoice">0</span>.
                    <p>';  
        
        echo $output;
    }

    public function store(Request $request){
        $values = $request->get('values');
        $ven_id = $request->get('ven_id');
        $ven_name = $request->get('ven_name');
        $data = $request->get('data');

        foreach($values as $index => $value){            ;
            $payVendor = new PayVendor;
            $payVendor->vendor_id = $ven_id;
            $payVendor->vendor_name = $ven_name;            
            $payVendor->foodItem = $value;
            $payVendor->data = json_encode($data[$index]);
            $payVendor->save();

            //To send a notification to inventory manager
            $noti = new Notification;
            $noti->from = 'Accountant';
            $noti->to = 'Inventory Manager';
            $noti->read = 0;
            $noti->data = 'Update '.$value." to the Inventory";
            $noti->save();   

             //To send a notification to admin
             $noti1 = new Notification;
             $noti1->from = 'Accountant';
             $noti1->to = 'Admin';
             $noti1->read = 0;
             $noti1->data = 'Update '.$value." to the Inventory";
             $noti1->save();   
        }
        
        
        // return redirect()->back()->with('success', 'Success!');  
    }
}
