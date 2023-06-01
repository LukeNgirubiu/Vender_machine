<?php

namespace App\Http\Controllers;
use App\Models\Coins;
use App\Models\Slots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Maintenance extends Controller
{
 
    public function update_stock(Request $request){
        // 'name'
        // 'quantity'
            try{
              $slot=Slots::where('name',$request->name)->get()->first();
              if($slot==NULL){
                return response("Product slot not found",404);
              }
              else{
                $slot->quantity=$request->quantity+intval($slot->quantity);
                $slot->save();
                return response("Stock for ".$request->name." updated",202);
              }
            }
            catch (\Exception $ex) {
                return response("Server error",500);
            }
    }

    public function stock_withdrawal(Request $request){
        /* 'name'
        'quantity' */
            try{
              $slot=Slots::where('name',$request->name)->get()->first();
              if($slot==NULL){
                return response("Product slot not found",404);
              }
              elseif($slot->quantity>$request){
                $slot->quantity=$request->quantity+intval($slot->quantity);
                $slot->save();
                return response("Stock for ".$request->name." withdrawn",202);
              }
              else{
                return response("Quantity requested for withdrawal is larger than available",404);
              }
            }
            catch (\Exception $ex) {
                return response("Server error",500);
            }
    }
    public function update_price(Request $request){
        /*
           cost,name
         */
            try{
              $slot=Slots::where('name',$request->name)->get()->first();
              if($slot==NULL){
                return response("Product slot not found",404);
              }
              else{
                $slot->cost=$request->cost;
                $slot->save();
                return response("Cost for ".$request->name." Updated",202);
              }
            }
            catch (\Exception $ex) {
                return response("Server error",500);
            }
    }
    public function show_slots(){
        $all_slots=DB::table('slots')
            ->select('name','cost','quantity')
            ->get();

        return $all_slots;
    }


    //Coins

    public function add_coins(Request $request){
        try{
        /*
        Example of the body
        {
  "5": 0,
  "10": 3,
  "20": 0,
  "40": 2
}
         */

         $request_coins=$request->all();
         $arr_coins=array("5","10","20","40");
         //Validation of coin Key
         foreach($request_coins as $request_coin=>$quantity){
            if(!in_array($request_coin,$arr_coins)){
              return  response("Coins of ".$request_coin." can't be added",404);;
            }
          }
         foreach($request_coins as $request_coin=>$quantity){
          $coins=Coins::where('name',$request_coin)->get()->first();
            $coins->quantity= intval($coins->quantity)+$quantity;
            $coins->save();
          }
          return response("Coins added",202);
         }
        catch (\Exception $ex) {
      return response("Server error ",500);
        }
    }
    public function show_coins(){
        $all_coins=DB::table('coins')
        ->select('name','quantity')
        ->get();
    return  $all_coins;
    }
    
    public function cash_withdrawal(Request $request){
        /*{
  "5": 0,
  "10": 3,
  "20": 0,
  "40": 2
}
         */
        $request_coins=$request->all();
        $flag=$this->validate_coins($request_coins);
        if($flag[0]==0){
          foreach($request_coins as $request_coin=>$quantity){
            $update_coin=Coins::where('name',$request_coin)->get()->first();
            $update_coin->quantity=intval($update_coin->quantity)-$quantity;
            $update_coin->save();
       }
        return response(array("status"=>"success","transaction"=>"Coins withdrawal", "coins"=>$request_coins),202);
        }
        elseif($flag[0]==2){
          return  response(array("status"=>"failed","transaction"=>"Coins of ".$flag[1]." not sufficient"),404);
        }
        elseif($flag[0]==2){
        return response(array("status"=>"failed","transaction"=>"Coins of ".$flag[1]." doesn't exist"),404);
        }
        else{
          return response(array("status"=>"failed","transaction"=>"Consult the installer"),500);
        }
    }
    private function validate_coins($request_coins){
      $coins=DB::table('coins')
      ->select('name','quantity')
      ->get()->toArray();
      $arr_coins=array("5"=>0,"10"=>0,"20"=>0,"40"=>0);
      foreach($coins as $coin){
          $arr_coins[$coin->name]=intval($coin->quantity);
      }
      foreach($request_coins as $request_coin=>$quantity){
          if(!array_key_exists($request_coin, $arr_coins)){
              return array(1,$request_coin);
          }
          if($arr_coins[$request_coin]<$quantity){
              return array(2,$request_coin);
          }
      }
      return array(0,"");
    }
}

//   echo gettype($all_slots);
//   print_r($ex->getMessage());
//echo $ex->statusCode; die;