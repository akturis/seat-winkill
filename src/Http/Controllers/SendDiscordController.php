<?php

namespace Seat\Akturis\WinKill\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use Seat\Akturis\WinKill\Http\Controllers\WinKillController;
use Seat\Akturis\WinKill\Notifications\KillPosted;


trait SendDiscordController
{
    public function __construct() {
    }    
    
    static public function SendDiscordKill(WinKillController $winkill) {
        if(!empty($whinkill->win_items)and($winkill->integration)){
            Notification::send($winkill, new KillPosted());
        }        
    }
}
