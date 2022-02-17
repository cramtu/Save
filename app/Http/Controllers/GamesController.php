<?php

namespace App\Http\Controllers;

use App\Models\GamesScoreBoard;
use App\Models\GamesTable;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    public function getgames(Request $request){

            $data=GamesTable::all();

        return response()->json([
            'item' => $data,
        ]);

    }

    public function getscoreboard(Request $request){


        $data=GamesScoreBoard::where('game_id',$request->game_id)->orderBy('score', 'desc')->take(25)->get();


            return response()->json([
                'item' => $data,
            ]);



    }

    public function addscore(Request $request){

        $data=GamesScoreBoard::where('game_id',$request->game_id)
            ->where('user_id',$request->user_id)->get()->first();



        if($data==null){

            GamesScoreBoard::insert([
                'user_id'=>$request->user_id,
                'game_id' => $request->game_id,
                'score' => $request->score,
                'rank'=>0,
            ]);
            $newgame=$this->sort($request);

            $oldrecord="your first game";

        }
        else{
            if($request->score > $data->score){
                $oldrecord=$data->rank;
                GamesScoreBoard::where('game_id',$request->game_id)
                    ->where('user_id',$request->user_id)->update([
                        'score'=>$request->score,
                    ]);
                $newgame=$this->sort($request);

            }else{

                return response()->json([
                    'item' => false,
                ]);


            }

        }

        return response()->json([
            'item' => $newgame,
            'oldrecord'=>$oldrecord,
        ]);



    }

    private function sort($request){

        $games=GamesScoreBoard::where('game_id',$request->game_id)->orderBy('score', 'desc')->get();

        foreach ($games as $key=>$game){
            GamesScoreBoard::where('user_id',$game->user_id)->update([
                'rank'=>$key+1,
            ]);
        }
        $newgame=GamesScoreBoard::where('game_id',$request->game_id)
            ->where('user_id',$request->user_id)->get()->first();

        return $newgame;

    }
}
