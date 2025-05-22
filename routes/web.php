<?php

use App\Http\Controllers\registrationControll;
use App\Http\Controllers\WebScraper;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view("welcome");
});

Route::get('/about', function () {
    return "Wellcome to this site where we made easy for others to find good food things!";
})->name("about");
Route::get('/registration', [registrationControll::class, "registration"])->name("registration");

Route::get('/scrape', [WebScraper::class, "index"])->name("scrape");
Route::get('/scrape_data', [WebScraper::class, "getWebData"])->name("scrape_data");
Route::get("/another", function(){
    return view("another");
})->name("another");

Route::get("/test", [WebScraper::class, "authTest"])->name("testing_auth");