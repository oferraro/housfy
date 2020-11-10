<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\Office;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

# TODO, implement security, use auth:api middleware, for this test it's not applied
Route::get('offices', function() {
    return Office::all();
});

Route::get('offices/{id}', function($id) {
    // TODO: Use dependency inversion to cache, save, etc with domain class Office

    // TODO: Check if it's cached
    $redisHelper = new RedisHelper();
    $cachedOffice = $redisHelper->get('office:'.$id);

    if (!$cachedOffice) {
        $office = Office::find($id);
        if (env('APP_ENV')) { // TODO: move this to a helper or use Laravel logger (it only logs in local environment)
            error_log("from DB and send to cache \n", 3, '/tmp/log');
        }
        $queueController = new App\Http\Controllers\QueueController();
        $queueController->enqueueOfficeForCache($office);
        // send to the queue
    } else {
        if (env('APP_ENV')) { // TODO: move this to a helper
            error_log("from cache \n", 3, '/tmp/log');
        }
        $office = json_decode($cachedOffice);
    }
    return response()
        ->json($office);
});

Route::post('offices', function(Request $request) {
    // TODO: Decide if cache or not new offices
    // TODO: Use a Domain class with Dependency Inversion and control the input there before call create
    return Office::create($request->all());
});

Route::put('offices/{id}', function(Request $request, $id) {
    $office = Office::findOrFail($id);
    $office->update($request->all());
    return $office;
});

Route::delete('offices/{id}', function($id) {
    // TODO: Send cached office delete to the queue
    Office::find($id)->delete();
    return 204;
});
