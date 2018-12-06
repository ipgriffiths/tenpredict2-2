<?php

namespace App\Http\Controllers;

use App\SuddenDeath;
use App\SuddenDeathPicks;
use App\Week;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SuddenDeathPicksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $team = $_POST['team'];
        
        //GET CURRENT WEEK
        $now = Carbon::now()->toDateTimeString();
        $weeks = Week::whereHas('games')->orderBy('play_week_num', 'DESC')->get();
        foreach ($weeks as $week) {
            $week_monday = Carbon::parse($week->week_saturday)->subDays(5)->toDateString();
            $week_sunday = Carbon::parse($week->week_saturday)->addDays(1)->toDateString();
            if ($now > Carbon::parse($week_monday)->startOfDay() && $now < Carbon::parse($week_sunday)->endOfDay()) {
                $current_week = $week->id;
            }
        }

        $sd = SuddenDeath::orderBy('start_week_id', 'DESC')->first();
        $check = SuddenDeathPicks::where('user_id', $user_id)
                                 ->where('sudden_death_id', $sd->id)
                                 ->where('week_id', $current_week)
                                 ->first();
        // return $check;

        if($check) {
            return SuddenDeathPicks::where('id', $check->id)->update(['team_picked' => $team]);
        } else {
            SuddenDeathPicks::insert([
                'sudden_death_id' => $sd->id,
                'user_id' => $user_id,
                'week_id' => $current_week,
                'team_picked' => $team,
                'datetime_picked' => $now
            ]);
            return 1;
        }

        return 0;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SuddenDeathPicks  $suddenDeathPicks
     * @return \Illuminate\Http\Response
     */
    public function show(SuddenDeathPicks $suddenDeathPicks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SuddenDeathPicks  $suddenDeathPicks
     * @return \Illuminate\Http\Response
     */
    public function edit(SuddenDeathPicks $suddenDeathPicks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SuddenDeathPicks  $suddenDeathPicks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuddenDeathPicks $suddenDeathPicks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SuddenDeathPicks  $suddenDeathPicks
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuddenDeathPicks $suddenDeathPicks)
    {
        //
    }
}
