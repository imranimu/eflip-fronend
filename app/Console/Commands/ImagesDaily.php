<?php

namespace App\Console\Commands;

use App\Models\Website;
use Helper;
use Config;
use DB;
use Illuminate\Console\Command;

class ImagesDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update images daily';

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
            // select(DB::raw('id, image_capture_attempt, hide_element_class, image_url, url, if(image_updated_at, time_to_sec(timediff(NOW(), image_updated_at)) / 3600, 24)  as hour_diff, if(image_capture_started_at, time_to_sec(timediff(NOW(), image_capture_started_at)) / 3600, 1)  as hour_diff_attempt, NOW() as now, image_updated_at'))
            // ->whereRaw('image_url IS NOT NULL')
            whereNotNull('image_url')
            ->whereRaw('LOWER(get_image_hourly) = "d"')
            // ->where('image_capture_attempt', '<', Config::get('settings.max_attempt_per_image'))
            // ->where('websites.status', 0)
            // ->orderByRaw('hour_diff DESC')
            // ->having('hour_diff', '>=', 24)
            // ->having('hour_diff_attempt', '>=', 1)
            // ->limit(5)
            ->get()->toArray();

       
        info(count($websites).' image caputre daily started');

        foreach ($websites as $website) {

            Website::where('id', $website['id'])->increment('image_capture_attempt');
            Website::where('id', $website['id'])->update(array('image_capture_started_at' => date('Y-m-d H:i:s')));

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

        info($image_captured_count . ' daily images captured finished') ;
    }
}
