<?php
namespace App\Http\Controllers;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class GestioneAllianz extends Controller
{
    public function userLogin(string $userCar)
    {
        $username = "ARDBRUNETTI";
        $password = "Motoraby2025?";

        Http::globalOptions([RequestOptions::COOKIES => new CookieJar()]);

        // Start with a get request to the main page
        $response = Http::get("https://portaleagenzie.allianz.it/motor/inquiry/ania/");
        //dd($response);
        if ($response->status() != 200) {
            return view("/test", ["userData" => null]);
        }
        $crawler          = new Crawler($response->body());
        $temp_form_action = $crawler->filter("form")->attr('action');
        $url              = "https://amlogin.allianz.it" . $temp_form_action; //after redirecting enough we can get the link to which we can do a post request to offcially get to the login page

        // Making a post requst to that link to actually get to the main login page
        $response = Http::post($url);
        if ($response->status() != 200) {
            return view("/test", ["userData" => null]);
        }
        $crawler          = new Crawler($response->body());
        $temp_form_action = $crawler->filter("form")->attr('action'); // Here we get the action of the form to where we would send the data
        $response         = Http::asForm()->post($temp_form_action, [
            'Ecom_User_ID'  => $username,
            'Ecom_Password' => $password,
        ]);

        if ($response->status() != 200) {
            return view("/test", ["userData" => null]);
        }

        //now we do a post request to the main page to check if login has done it successfully
        $response = Http::get("https://portaleagenzie.allianz.it/motor/inquiry/ania/");
        if ($response->status() != 200) {
            return view("/test", ["userData" => null]);
        }

        //initSession
        $response = Http::post("https://portaleagenzie.allianz.it/motor/inquiry/ania-bff/main/initSession");
        //getDatiServer
        $response = Http::get("https://portaleagenzie.allianz.it/motor/inquiry/ania-bff/main/getDatiServer");

        //now searching by userCar code
        $url = str_replace("ania/", "ania-bff/ricerca/getRicercaPerTarga/", "https://portaleagenzie.allianz.it/motor/inquiry/ania/") . $userCar . "?sigla=";

        $response = Http::get($url);      // The json returns 3 key value pairs: DatiImmatricolazioni, DatiAttestati and DatiCoperture
                                          //dd($response);
                                          //dd($url);
        if ($response->status() != 200) { //something went wrong
            return view("/test", ["userData" => null]);
        }

        die($response->body());

        $visura = $response->json(); //get's the actual json of all the data about user's car

        $immatricolazioni = Arr::get($visura, "DatiImmatricolazioni.0", null);
        return response()->json($immatricolazioni);

        if (empty($dataAcquired)) {
            return view("/test", ["userData" => null]);
        }

        return view("/test", ["userData" => $dataAcquired]);
    }

    public function userSearch(string $userCar)
    {

        $url = str_replace("ania/", "ania-bff/ricerca/getRicercaPerTarga/", "https://portaleagenzie.allianz.it/motor/inquiry/ania/") . $userCar . "?sigla=";

        $response = Http::get("https://portaleagenzie.allianz.it/motor/inquiry/ania/");

        if ($response->status() != 200) { //check if the site is working, if not returns the userData to be empty
            return view("/test", ["userData" => null]);
        }

        // The json returns 3 key value pairs: DatiImmatricolazioni, DatiAttestati and DatiCoperture
        $response = Http::get($url);

        if ($response->status() != 200) { //something went wrong
            return view("/test", ["userData" => null]);
        }
        $dataAcquired = $response->body(); //get's the actual json of all the data about user's car!
                                           //dd($url);
        if (empty($dataAcquired)) {
            return view("/test", ["userData" => null]);
        }

        //return view("/test", ["userData" => $dataAcquired]);

    }
}

class SIVI
{
    public string $data_immatricolazione;
    public string $provincia_emissione_carta;
    public string $tipo_veicolo;
    public string $intestatario_cf;
    public string $intestatario_istat_provincia;
    public string $intestatario_istat_comune;
    public string $intestatario_provincia;
    public string $intestatario_comune;

    //   "uso": "",
    //   "alimentazione": "G",
    //   "potenza": 55,
    //   "cilindrata": 1120,
    //   "cavalli_fiscali": 14,
    //   "peso": 1640,
    //   "peso_tara": 1200,
    //   "peso_rimorchiabile": 800,
    //   "marce": 0,
    //   "posti": 5,
    //   "codice_omologazione": "0000000000027693",
    //   "is_svuotato": false,
    //   "is_valid": true
}
