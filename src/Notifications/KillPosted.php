<?php

namespace Seat\Akturis\WinKill\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Kassie\Calendar\Helpers\Helper;


class KillPosted extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
//        $attachment = Helper::BuildSlackNotificationAttachment($notifiable);
        $fields = array();
        $content = "**Поздравим победителей розыгрыша!**\n";
        $content .= implode("\n", array_map(function ($entry) {
                        $connector = '<@!'.$entry['character']['connector_id'].'>/'.$entry['character']['name'];
                        $character_name = $entry['character']['connector_id']?$connector:$entry['character']['name'];
                        return $character_name.' - **'.
                               $entry['item']['name'].'** - `'.number_format($entry['item']['price']).'` - '.
                               $entry['item']['qty'].' шт';
                    }, $notifiable->win_items));
//        dd($notifiable,$notifiable->win_items, $content);
//        $content .= '<@169173796413112321>';
        return (new SlackMessage)
            ->success()
            ->from('WinKill', ':calendar:')
//            ->content(trans('calendar::seat.notification_new_operation'))
            ->content($content)
            ->attachment(function ($attachment) use($notifiable) {
                //dd($attachment);
                $attachment->title('Winnners for '.sprintf("https://zkillboard.com/kill/%d", $notifiable->killmail_id ));
            });
    }
}
