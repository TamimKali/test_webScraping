<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use View;
use function PHPUnit\Framework\throwException;

class WebScraper extends Controller
{

    public function index(){
        return view("scrape");
    }
    public function getWebData(Request $request)
    {
        //the span with the class-->a-price aok-align-center reinventPricePriceToPayMargin priceToPay
        //contains the price
        $url = $request->query('site');
    
        try {
            if(filter_var($url, FILTER_VALIDATE_URL)){
                $client = new Client();
                $parsedUrl = parse_url($url);
                if(isset($parsedUrl['host']) && $parsedUrl['host'] != ''){
                if(str_contains($parsedUrl['host'], 'amazon.it')){
                    $response = $client->request('GET', $url, [
                        'headers' => [
                            'User-Agent' => 'Mozilla/5.0', // Mimic browser
                        ],
                    ]);
                    if($response->getStatusCode() == 200){ 
                        
                    if($response->getHeaderLine('Content-Type') === 'text/html;charset=UTF-8'){
                        $all_info = [];
                        
                        $crawler = new Crawler((string) $response->getBody());
                        try { // get the title, index 0
                            //$title_product = $crawler->filter('span#productTitle')->text();

                            $all_info[] = $crawler->filter('span#productTitle')->text();
                        } catch (\InvalidArgumentException $e) {
                            $all_info[] = null;

                            //$title_product = "Title not found!";
                        }

                        try { // get the offer price percentage and original price, if there is, index 1 if null, no discount
                            $all_info[] = $crawler->filter('span.savingsPercentage')->text();
                            $all_info[] =  $crawler->filter('span.basisPrice > span > span.a-offscreen')->text();
                            //$price_discount_percentage = $crawler->filter('span.savingsPercentage')->text();
                            //$price_discount = $crawler->filter('span.basisPrice > span > span.a-offscreen')->text();
                        } catch (\InvalidArgumentException $e) {
                            $all_info[] = null;
                            //$price_discount_percentage = 'No discount';
                            //$price_discount = "No discount";
                        }

                        try { // get the actual price of the product, index 2 if no discount, index 3 if discount
                            $all_info[] = $crawler->filter('span.a-price.aok-align-center > .a-offscreen')->text();
                            //$price_product = $crawler->filter('span.a-price.aok-align-center > .a-offscreen')->text();
                        } catch (\InvalidArgumentException $e) {
                            $all_info[] = null;
                            //$price_product = "Price not found!";
                        }
                            
                        return view("another", ['product_info' => $all_info]);
                    }
                        
                    }
                    elseif ($response->getStatusCode() == 404) {
                        return view("another", ['titles' => "Try turning on the internet!"]);
                    }
                    else {
                    return view("another", ['titles' => "Amazon confirmed!"]);
                    }
                }
                else{
                    return view("another", ['titles' => "Put an amazoon linkkk"]);
                }
                }
                else{
                    return view("another", ['titles' => "Put a urlllll"]);
                }
            
            }
            else{
                throw new Exception('Something went wrong! Try putting the correct url!');
            }
            
    
        } catch (\Exception $e) {
            return response()->view('another', [
                'titles' => [$e->getMessage()]
            ]);
        }
    }

    public function test(){
        return view('another', ["title_product" => "BROOOOO"]);
    }


}
