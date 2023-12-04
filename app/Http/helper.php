<?php

use App\Models\Setting;
use Carbon\Carbon;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Log;

// include(base_path()."/vendor/grabzIt/GrabzItClient.class.php");

Class Helper{

    public static function getSetting($slug){
        return Setting::where('slug',$slug)->first()->value??'';
    }

    public static function getTime($date, $updateType) {
        
        if (!empty($date)) {
            //$ca = Carbon::parse('2016-03-26 14:15:00');
            $ca = Carbon::parse($date);
            $diff = "";

            $seconds = $ca->diffInSeconds();
            $minutes = $ca->diffInMinutes();
            $hours = $ca->diffInHours();
            $days = $ca->diffInDays();

            if ($updateType == "W") { // weekly
                if ($days >= 1) {
                    $diff = $days . "d";
                } elseif ($hours >= 1) {
                    $diff = $hours . "h";
                } elseif ($minutes >= 1) {
                    $diff = $minutes . "m";
                } else {
                    $diff = $seconds . "s";
                }
            } elseif ($updateType == "D") { // daily
                if ($hours >= 1) {
                    $diff = $hours . "h";
                } elseif ($minutes >= 1) {
                    $diff = $minutes . "m";
                } else {
                    $diff = $seconds . "s";
                }
            } else if ($updateType == "H") { // monthly
                if ($minutes >= 1) {
                    $diff = $minutes . "m";
                } else {
                    $diff = $seconds . "s";
                }
            }
            return $diff;
        }

    }

    public static function browserShot($url,$imageName){

        if(!$imageName){
            $imageName = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "_", $url) . '_' . 'image.jpg';
        }
        
        try {
            
            Browsershot::url($url)
                ->noSandbox()
                ->setCustomTempPath(public_path('/tmp'))
                ->setOption('landscape', true)
                ->windowSize(1024, 768)
                ->setScreenshotType('jpeg', 30)
                ->setNodeBinary('/bin/node')
                ->setNpmBinary('/bin/npm')
                -> setChromePath('/bin/chromium-browser')
                ->waitUntilNetworkIdle()
                ->addChromiumArguments([
                    'single-process',
                ])
                ->dismissDialogs()
                ->ignoreHttpsErrors()
                ->save(public_path("assets/website_images/original/" . $imageName));
            return array(
                'success' => true,
                'image_name' => $imageName
            );

        } catch (\RuntimeException $e) {
            Log::info($e);
            return array(
                'success' => false,
                'message' => "no image captured",
            );
        }

    }
    
    public static function bs($url){

        $imageName = "test.jpg";
            
            Browsershot::url($url)
                ->noSandbox()
                ->setCustomTempPath('/home/eflip/test/tmp')
                ->setOption('landscape', true)
                ->windowSize(1024, 768)
                ->setScreenshotType('jpeg', 30)
                ->setNodeBinary('/bin/node')
                ->setNpmBinary('/bin/npm')
                -> setChromePath('/bin/google-chrome-stable')
                ->waitUntilNetworkIdle()
                ->addChromiumArguments([
                    'single-process',
                ])
                ->dismissDialogs()
                ->ignoreHttpsErrors()
                ->save("/home/eflip/test/" . $imageName);
            return array(
                'success' => true,
                'image_name' => $imageName
            );

    }

    // public static function generateImageGrabzIt($url, $hideElementClass, $imageName = "")
    // {

    //     $wkhtmldir = public_path() . '/assets/website_images/wkhtml/';
    //     $originalDir = public_path() . '/assets/website_images/original/';

        
    //     $url=str_replace('https://','',$url);
    //     $url=str_replace('http://','',$url);

    //     if(!$imageName){
    //         $imageName = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "_", $url) . '_' . 'image.jpg';
    //     }
    //     // generate image
    //     try {
    //         $grabzIt = new GrabzItClient(Config::get("settings.grabzItKey"), Config::get("settings.grabzItSecret"));
    //         $options = new GrabzItImageOptions();
    //         $options->setWidth(1024);
    //         $options->setHeight(768);
    //         $options->setDelay(10000);
    //         //$options->setRequestAs(2);
    //         $options->setCountry("US");
    //         if ($hideElementClass) {
    //             $options->setHideElement($hideElementClass);
    //         }
    //         $grabzIt->URLToImage($url, $options);
    //         $grabzIt->SaveTo("assets/website_images/wkhtml/" . $imageName);
    //     } catch (\RuntimeException $e) {
    //         return array(
    //             'success' => false,
    //         );
    //     }

    //     // if file is created then replace it and remove it from temp directory i.e. wkhtml
    //     if (File::exists($wkhtmldir . $imageName)) { // success
    //         Image::make($wkhtmldir . $imageName)->save($originalDir . $imageName);
    //         //->resize(312, 170)->save($dir . $imageName);
    //         // delete
    //         File::delete($wkhtmldir . $imageName);

    //         return array(
    //             'success' => true,
    //             'image_name' => $imageName
    //         );
    //     } else { // fail
    //         return array(
    //             'success' => false,
    //             'message' => "no image captured",
    //         );
    //     }

    // }

   

}