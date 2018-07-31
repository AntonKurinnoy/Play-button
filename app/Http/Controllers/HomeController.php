<?php

namespace App\Http\Controllers;

use App\Stuff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $stuff = $user->stuff;

        return view('home',[
            'bonusPoints' => $user->bonus_points,
            'stuff' => $stuff
        ]);
    }

    public function play(){

        /***
         * получаем тип приза
         *
         * 1 - денежный приз
         * 2 - бонусные баллы
         * 3 - физический предмет
         */

        $prize = ['денежный приз','бонусные баллы','физический предмет'];
        //проверяем позиции, по которым можно проводить розыгрыш
        $user = Auth::user();
        $arr = [2];
        if ($user->max_money_to_win > 0)
            $arr[] = 1;
        if ($user->max_stuff_to_win > 0)
            $arr[] = 3;

        $type = $arr[array_rand($arr)];

        if ($type === 1){
            $sum = rand(1,100);

            //проверяем на максимально возможную сумму для выиграша
            $user = Auth::user();
            if ($user->max_money_to_win < $sum)
                $sum = $user->max_money_to_win;

            $bonusPoints = round($sum * 0.7);

            return response()->json([
                'prize' => $prize[$type-1],
                'sum' => $sum,
                'bonusPoints' => $bonusPoints
            ]);
        } elseif ($type === 2){

            $bonusPoints = rand(10,20);

            return response()->json([
                'prize' => $prize[$type-1],
                'bonusPoints' => $bonusPoints
            ]);
        } elseif ($type === 3){

            $stuff = Stuff::pluck('id')->all();
            $id = $stuff[array_rand($stuff)];
            $stuff = Stuff::find($id);

            return response()->json([
                'prize' => $prize[$type-1],
                'stuff' => $stuff->name,
                'id' => $stuff->id
            ]);
        }


        return response()->json(['prize'=> $prize[$type-1]]);
    }

    public function prize(Request $request){

        $prize = $request->all()['data']['prize'];
        $user = Auth::user();
        if ($user->timer < time()){
            $user->timer = time() + 24*60*60;
            $user->max_money_to_win = 100;
            $user->max_stuff_to_win = 3;
        }

        //если игрок выбрал деньги
        if ($prize === "money"){
            $user->max_money_to_win = $user->max_money_to_win - $request->all()['data']['sum'];
            $user->save();

        //если игрок выбрал баллы
        } elseif ($prize === "bonusPoints"){
            $user->bonus_points = $user->bonus_points + $request->all()['data']['sum'];
            $user->save();

        //оставшийся вариант с предметами
        } else {
            DB::table('stuff_to_send')->insert([
                'stuff_id' => $request->all()['data']['id'],
                'status'=> 'отправить товар',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $user->max_stuff_to_win = $user->max_stuff_to_win - 1;
            $user->stuff .= $request->all()['data']['prize']." ";
            $user->save();
        }

        return response()->json(['result'=> 'ok']);
    }

}
