<?php
namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use View;

class WebScraper extends Controller
{
    public function index()
    {
        return view("scrape");
    }

    public function getWebData(Request $request)
    {
        //the span with the class-->a-price aok-align-center reinventPricePriceToPayMargin priceToPay
        //contains the price
        $url = $request->query('site');

        try {

            if (! filter_var($url, FILTER_VALIDATE_URL)) {
                throw new Exception('Something went wrong! Try putting the correct url!');
            }

            $client    = new Client();
            $parsedUrl = parse_url($url);

            if (! isset($parsedUrl['host']) || $parsedUrl['host'] == '') {
                return view("another", ['error' => "Put a urlllll"]);
            }

            $response = $client->request('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0', // Mimic browser
                ],
            ]);

            if ($response->getStatusCode() == 404) {
                return view("another", ['error' => "Try turning on the internet!"]);
            }

            if ($response->getStatusCode() != 200) {
                return view("another", ['error' => "Amazon confirmed!"]);
            }

            if ($response->getHeaderLine('Content-Type') !== 'text/html;charset=UTF-8') {
                return view("another", ["titles" => "Qualcosa non va!"]);
            }

            $crawler = new Crawler((string) $response->getBody());

            $product = new ProdottoAmazon();

            try {
                $product->title = $crawler->filter('span#productTitle')->text();
            } catch (\InvalidArgumentException $e) {
                $product->title = null;
            }

            try { // check if it's a limited time offer
                $product->limited_time_offer = $crawler->filter('span#dealBadgeSupportingText')->text();
            } catch (\InvalidArgumentException $e) {
                $product->limited_time_offer = null;
            }

            // try {                                                                                // get the offer price percentage and original price, if there is, index 2 if null, no discount
            //     $all_info[] = $crawler->filter('span.savingsPercentage')->text();                    //percentage of discount
            //     $all_info[] = $crawler->filter('span.basisPrice > span > span.a-offscreen')->text(); //original price before discount
            //                                                                                          //$price_discount_percentage = $crawler->filter('span.savingsPercentage')->text();
            //                                                                                          //$price_discount = $crawler->filter('span.basisPrice > span > span.a-offscreen')->text();
            // } catch (\InvalidArgumentException $e) {
            //     $all_info[] = null;
            //     //$price_discount_percentage = 'No discount';
            //     //$price_discount = "No discount";
            // }

            // try {                                                                                   // get the actual price of the product, index 3 if no discount, index 4 if discount
            //     $all_info[] = $crawler->filter('span.a-price.aok-align-center > .a-offscreen')->text(); //actual price
            //                                                                                             //$price_product = $crawler->filter('span.a-price.aok-align-center > .a-offscreen')->text();
            // } catch (\InvalidArgumentException $e) {
            //     $all_info[] = null;
            //     //$price_product = "Price not found!";
            // }

            // try {                                                            // get the picture of the product, index 4 if no discount, index 5 if discount
            //     $all_info[] = $crawler->filter('img#landingImage')->attr('src'); //actual price
            //                                                                      //$price_product = $crawler->filter('span.a-price.aok-align-center > .a-offscreen')->text();
            // } catch (\InvalidArgumentException $e) {
            //     $all_info[] = null;
            //     //$price_product = "Price not found!";
            // }

            // try {                                                           // check if theres any coupon, index 5 if no discount, index 6 if discount
            //     $all_info[] = $crawler->filter('span.couponLabelText')->text(); //actual price
            //                                                                     //$price_product = $crawler->filter('span.a-price.aok-align-center > .a-offscreen')->text();
            // } catch (\InvalidArgumentException $e) {
            //     $all_info[] = null;
            //     //$price_product = "Price not found!";
            // }

            return view("another", ['product' => $product]);

        } catch (\Exception $e) {
            return response()->view('another', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function test()
    {
        return view('another', ["title_product" => "BROOOOO"]);
    }

    public function authTest()
    {
        Http::globalOptions(['cookies' => new CookieJar(), 'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0',
            'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        ]]);

        $username = "claudioscatoli@navert.it";
        $password = "19913050Ah+";

        $response = Http::get("https://sentry2.quantumquote.it/auth/login/sentry/");

        $crawler              = new Crawler($response);
        $csrf_token           = $crawler->filter('input[name=csrfmiddlewaretoken]')->attr('value');
        $url_post_credenziali = $crawler->filter('#login form')->attr('action');
        if ($url_post_credenziali === '') {
            $url_post_credenziali = "https://sentry2.quantumquote.it/auth/login/sentry/";
        }

        $response_post = Http::asForm()->post($url_post_credenziali, [
            'username'            => $username,
            'password'            => $password,
            'op'                  => 'login',
            "csrfmiddlewaretoken" => $csrf_token,
        ]);

        die($response_post);

        $client    = new Client([RequestOptions::COOKIES => true, RequestOptions::ALLOW_REDIRECTS => false]);
        $response  = $client->get("https://sentry2.quantumquote.it/auth/login/sentry/");
        $crawler   = new Crawler((string) $response->getBody());
        $csrfToken = $crawler->filter('input[name=csrfmiddlewaretoken]')->attr("value");
        // var_dump($csrfToken);

        // try {
        $response = $client->post("https://sentry2.quantumquote.it/auth/login/sentry/", [
            'form_params' => [
                'csrfmiddlewaretoken' => $csrfToken,
                'op'                  => 'login',
                'username'            => 'claudioscatoli@navert.it',
                'password'            => '19913050Ah+',
            ],
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Referer'      => 'https://sentry2.quantumquote.it/auth/login/sentry/',
                'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0',
                'Accept'       => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);
        // } catch (Exception $exc) {
        //     dd($exc);
        // }

        if ($response->getStatusCode() != 302) {
            return view("/test", ["response" => null]);
        }

        return response($response->getStatusCode());
    }

}

class ProdottoAmazon
{
    public ?string $title;
    public ?string $actual_price;
    public ?string $limited_time_offer;
    public ?string $discount_percentage;
    public ?string $original_price;
    public ?string $picture;
    public ?string $coupon;

    public function __construct()
    {
    }
}
