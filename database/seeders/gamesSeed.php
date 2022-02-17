<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class gamesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0;$i<100; $i++) {
            DB::table('gamestable')->insert([
                'title' => $faker->company,
                'unique_users' => rand(0, 100),
                'total_play_count' => rand(0, 10000),
            ]);
        }

        for ($i = 0;$i<5000; $i++) {
            DB::table('gamesscoreboard')->insert([
                'game_id' => rand(0,100),
                'user_id'=>rand(0,1000),
                'score' => rand(0,5),
                'rank'=>0,
            ]);
        }



        $scores=DB::table('gamesscoreboard')->orderBy('score', 'desc')->get()->groupBy('game_id');
        foreach ($scores as $score){
            foreach ($score as $key=>$scoredata){
                DB::table('gamesscoreboard')->where('user_id',$scoredata->user_id)->update([
                    'rank'=>$key+1,
                ]);
            }
        }
    }
}
