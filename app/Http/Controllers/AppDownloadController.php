<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppDownloadController extends Controller
{
    public function customerDownload(){

        {

            $filePath = storage_path('app/KTM_Final.apk');

            // Check if the file exists
            if (file_exists($filePath)) {
                // Return the file as a response
                return response()->download($filePath);
            } else {
                // Handle file not found
                abort(404);
            }
        }
    }

    public function driverDownload(){
        $filePath = storage_path('app/KTM_Final.apk');

            // Check if the file exists
            if (file_exists($filePath)) {
                // Return the file as a response
                return response()->download($filePath);
            } else {
                // Handle file not found
                abort(404);
            }
    }
}
