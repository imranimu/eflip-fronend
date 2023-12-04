<?php

namespace App\Console\Commands;

use App\Models\Website;
use Helper;
use Config;
use DB;
use Illuminate\Console\Command;

class ImagesWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update images weekly';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        set_time_limit(0);

        $image_captured_count = 0;

        // website with no images
        $websites = Website::
            // select(DB::raw('id, image_capture_attempt, hide_element_class, image_url, url, if(image_updated_at, time_to_sec(timediff(NOW(), image_updated_at)) / 3600, 168) as hour_diff, NOW() as now, image_updated_at'))
            // ->whereRaw('image_url IS NOT NULL')
            whereNotNull('image_url')
            ->whereRaw('LOWER(get_image_hourly) = "w"')
            // ->where('image_capture_attempt', '<', Config::get('settings.max_attempt_per_image'))
            // ->where('websites.status', 0)
            // ->orderByRaw('hour_diff DESC')
            // ->having('hour_diff', '>=', 168)
            // ->limit(5)
            ->get()->toArray();

        info(count($websites).' image caputre weekly started');

        foreach ($websites as $website) {

            // increment image capture attempt
            Website::where('id', $website['id'])->increment('image_capture_attempt');

            // generate image
            $result = Helper::browserShot($website['url'], $website['image_url']);

            if ($result['success']) { // success(true)
                // update image_url
                Website::where('id', $website['id'])->update(array('image_capture_attempt' => 0, 'image_updated_at' => date('Y-m-d H:i:s')));

                $image_captured_count++;
            }

            // $web = Website::where('id', $website['id'])->first();
            // if ($web['image_capture_attempt'] == Config::get('settings.max_attempt_per_image')) {
            //     //send email
            //     Mail::send('website::mail.websiteInfo', compact('web'), function ($message) use ($web) {
            //         $message->to(Config::get("settings.mail_send_to"))->subject(Config::get('settings.max_attempt_per_image') . " attempt reached for " . $web['name']);
            //     });
            // }

        }

        info($image_captured_count . ' weekly images captured finished') ;

    }
}
