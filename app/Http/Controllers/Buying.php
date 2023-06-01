<?php
namespace App\Http\Controllers;
use App\Models\Coins;
use App\Models\Slots;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class Buying extends Controller
{
    public function buy(Request $request){
      try{
        $request_body=$request->all();
        $coins=$request_body["coins"];
        $product=$request_body["slot"];
        $arr_coins=array("5","10","20","40");
        $cash_amount=0;
     foreach($coins as $request_coin=>$quantity){
        if(!in_array($request_coin,$arr_coins)){
          return  response(array("status"=>"failed","body"=>"Coins of ".$request_coin." doesn't exist"),404);
        }
        else{
            $amount=intval($request_coin)*$quantity;
            $cash_amount=$cash_amount+$amount;
        }
      }
      //500 Grams
      $slot=Slots::where('name',$product)->get()->first();
      if($slot==Null){
        return  response(array("status"=>"failed","body"=>"Product slot doesn't exist"),404);
      }
      if($cash_amount<$slot->cost){
        return  response(array("status"=>"failed","body"=>"Insufficient cash. Top up more coins"),404);
      }
      if($slot->quantity>0){
        $arr_coins=array("5"=>0,"10"=>0,"20"=>0,"40"=>0);
        $all_coins=DB::table('coins')
        ->select('name','quantity')
        ->get()->toArray();
        $balance=$cash_amount-$slot->cost;
        $avail_coins=array();
        foreach($all_coins as $ava_coin){
          array_push($avail_coins,$ava_coin->quantity);
        }
        
        foreach($coins as $req_coin=>$quant){
         $idx=self::get_index($req_coin);
         $avail_coins[$idx]=$avail_coins[$idx]+$quant;
        }
        if($balance==0){
          foreach($coins as $req_coin=>$quant){
            $final_coins=Coins::where('name',$req_coin)->get()->first();
            $final_coins->quantity= intval($final_coins->quantity)+$quant;
            $final_coins->save();
          }
          $slot->quantity=intval($slot->quantity)-1;
          $slot->save();
          $response_body="Purchased ".$slot->name."for ".$slot->cost.". Thank for buying from us";
          return response(array("status"=>"success","balance"=>"No balance","body"=>$response_body),201);
        }
        if($balance>0){
          $change=self::check_change($balance, $arr_coins,count($avail_coins)-1,$avail_coins);
          if($change['status']==="success"){
            $cal_coins=$change['new_coins'];
            $update_coins=array(array("name"=>"5","quantity"=>$cal_coins[0]),
            array("name"=>"10","quantity"=>$cal_coins[1]),
            array("name"=>"20","quantity"=>$cal_coins[2]),
            array("name"=>"40","quantity"=>$cal_coins[3])
          );
          foreach($update_coins as $update_coin){
            $final_coins=Coins::where('name',$update_coin["name"])->get()->first();
            $final_coins->quantity= $update_coin["quantity"];
            $final_coins->save();
            }
            $slot->quantity=intval($slot->quantity)-1;
            $slot->save();
            $change_balance=array();
            foreach($change["change"] as $change=>$value){
              if($value!=0){
                $change_balance[$change]=$value;
              }
            }
            $response_body="Purchased ".$slot->name." for ksh ".$slot->cost.". Thank you for buying from us";
            return response(array("status"=>"success","balance"=>$change_balance,"body"=> $response_body),201);
          }
          else{
            return response(array("status"=>"failed","body"=>"Vendor machine doesn't have enough coins to complete transaction"),404);
          }
        }
      }
      else{
        return response(array("status"=>"failed","body"=>"This slot is not available for now. Waiting untill it is replenished"),404);
      }
      }
      catch (\Exception $ex) {
        $error=$ex->getMessage();
        print_r($error);
        if(str_contains($error,"Undefined index")){
          $response_body=array("status"=>"failed","body"=>"Failed. Refer to the guide kindly");
            return response($response_body,404);
        }
        else{
          $response_body=array("status"=>"failed","body"=>"Machine error inform the assistant");
          return response($response_body,404);
        }
      }
    }

    private static function check_change($balance, $change, $index,$coins){
        $coin_values=array(5,10,20,40);
        $coin_keys=array("5","10","20","40");
        $new_index=$index;
        if(($coins[$index]==0)&&($index==0)){
        return array("status"=>"fail");
        }
       
        if(($coins[$index]==0)&&($index>0)){
            while($new_index>0){
               if($coins[$new_index]!=0){
                break;
               }
                $new_index=$new_index-1;
            }
        } 
      $new_balance=$balance-$coin_values[$new_index];
     if($new_balance>0){
      $change[$coin_keys[$new_index]]=$change[$coin_keys[$new_index]]+1;
      $coins[$new_index]=$coins[$new_index]-1;
      return self::check_change($new_balance,$change,$new_index, $coins);
     }
     if($new_balance==0){
      $change[$coin_keys[$new_index]]=$change[$coin_keys[$new_index]]+1;
      $coins[$new_index]=$coins[$new_index]-1;
      return array("status"=>"success","change"=>$change,"new_coins"=>$coins);
     }
     if($new_balance<0){
        return self::check_change($balance,$change,$new_index-1, $coins);
     }
    }
    private static function get_index($coin_key){
      switch($coin_key){
        case "5":
          return 0;
        case "10":
          return 1;
        case "20":
          return 2;
        case "40":
          return 3;
      }
    }
}
