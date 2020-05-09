<?php

namespace Seat\Akturis\WinKill\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Sde\InvType;
use Illuminate\Support\Facades\Cache;
use Seat\Web\Models\User;
use Seat\Services\Repositories\Corporation\Members;
use Illuminate\Http\Request;
use DataTables;
use GuzzleHttp\Client;
use Seat\Akturis\WinKill\Notifications\KillPosted;
use Seat\Notifications\Models\Integration;
use Warlof\Seat\Connector\Models\User as UserDiscord;
use Seat\Akturis\WinKill\Http\Controllers\SendDiscordController;

class WinKillController extends Controller
{
    use Notifiable;
    use SendDiscordController;
    
    public $win_items = [];
    public $killmail_id;
    public $integration;
    
    public function __construct() {
        if(setting('winkill.max_price', true) == ''){
            setting(['winkill.max_price', 10000000 ], true);
        }
        $this->integration = Integration::where('name','Win Kill')->first();        
    }
    
    public function index(Request $request) {
        if(!$this->integration){
            $error = 'Missing discord integration with name "Win Kill"';
            return view('winkill::winkill')->with('error', $error);
        }
        return view('winkill::winkill',compact('error'));
    }
    
    public function getDropFromKillmail(Request $request)
    {
        $killmail = $request->input('killmail');
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->get($killmail);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $res = $e->getResponse();
            return redirect()->back();
        }
        if($res->getStatusCode() == 200 ){
            $data = json_decode($res->getBody());
            $this->killmail_id = $data->killmail_id;
            $attackers = $data->attackers;
            $attackers = array_filter($attackers, function($obj){
                    if (empty($obj->character_id)) {
                        return false;
                    }
                    return true;
                });
            shuffle($attackers);
            
            $items = array_filter($data->victim->items, function($obj){
                    if (isset($obj->quantity_dropped)) {
                        return true;
                    }
                    return false;
                });
            $loot_items = [];
            foreach ($items as $key => $item) {
                $invType = InvType::with('prices')->where('typeID', $item->item_type_id)->first();
                $price = optional($invType->prices)->average_price;
                $name = $invType->typeName;
                if(($price != NULL) and ($price <= setting('winkill.max_price', true))) continue;
                for($i=1;$i<=$item->quantity_dropped;$i++){
                    $loot_items[] = ['item_id'=>$item->item_type_id,'name'=>$name,'price'=>$price,'qty'=>1];                 
                }
            }
            usort($loot_items, function($a, $b) {
                return $b['price'] <=> $a['price'];
            });
            
            $this->win_items = [];
            if(!empty($loot_items)) {
                for( $i = 0, $count = count($loot_items); $i < $count; $i++ ) {
                    if(!empty($attackers[$i])) {
                        $user = User::find($attackers[$i]->character_id);
                        if($user) {
                            $user_discord = UserDiscord::where('group_id',$user->group->id)->first();
                            $connector_id = $user_discord?$user_discord->connector_id:null;
                        } else {
                            $connector_id = null;
                        }
                        $eseye = app('esi-client')->get();
                        $character_info = $eseye->invoke('get', '/characters/{character_id}/', [
                            'character_id' => $attackers[$i]->character_id,
                        ]);
                        $name = $character_info->name;

                        $character = ['character_id'=>$attackers[$i]->character_id,'name'=>$name,'connector_id'=>$connector_id];

                        $this->win_items[] = ['character'=>$character,'item'=>$loot_items[$i]];                    
                    }
                }
            }
            if(!empty($this->win_items)and($this->integration)){
                if (auth()->user()->has('winkill.discord')) {
                    Notification::send($this, new KillPosted());
                }
            }
            return view('winkill::winkill',['win_items'=>$this->win_items,'killmail_id'=>$this->killmail_id]);
        }


    }
    
    public function routeNotificationForSlack()
    {
        return $this->integration->settings['url'];
    }
       
}

