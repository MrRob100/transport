<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DOMDocument;
use DOMXPath;

class BusTimesController extends Controller
{
    public function index()
    {
        return view('bus-times');
    }

    public function getBusTimes()
    {
        try {
            $response = Http::get('https://bustimes.org/stops/0190NSC30636');

            if ($response->successful()) {
                $html = $response->body();

                $dom = new DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadHTML($html);
                libxml_clear_errors();

                $xpath = new DOMXPath($dom);
                $departuresDiv = $xpath->query('//div[@id="departures"]')->item(0);

                $departuresHtml = '';
                if ($departuresDiv) {
                    // Remove <form> elements
                    $formElements = $xpath->query('.//form', $departuresDiv);
                    foreach ($formElements as $form) {
                        $form->parentNode->removeChild($form);
                    }

                    // Remove <p class="text"> elements
                    $textPElements = $xpath->query('.//p[@class="next"]', $departuresDiv);
                    foreach ($textPElements as $textP) {
                        $textP->parentNode->removeChild($textP);
                    }

                    // Add <hr> after each <a class="service"> element
                    $serviceLinks = $xpath->query('.//a[contains(@class, "service")]', $departuresDiv);
                    foreach ($serviceLinks as $serviceLink) {
                        $hr = $dom->createElement('hr');
                        $serviceLink->parentNode->insertBefore($hr, $serviceLink->nextSibling);
                    }

                    $departuresHtml = $dom->saveHTML($departuresDiv);
                }

                return response()->json([
                    'success' => true,
                    'data' => $departuresHtml
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to fetch bus times data.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTrainTimes()
    {
        try {
            $response = Http::get('https://www.realtimetrains.co.uk/search/simple/gb-nr:NLS/to/gb-nr:BRI');

            if ($response->successful()) {
                $html = $response->body();

                $dom = new DOMDocument();
                libxml_use_internal_errors(true);
                $dom->loadHTML($html);
                libxml_clear_errors();

                $xpath = new DOMXPath($dom);
                $serviceListDiv = $xpath->query('//div[@class="servicelist"]')->item(0);

                $trainTimesHtml = '';
                if ($serviceListDiv) {
                    // Remove <form> elements
                    $formElements = $xpath->query('.//form', $serviceListDiv);
                    foreach ($formElements as $form) {
                        $form->parentNode->removeChild($form);
                    }

                    // Remove divs with class "upsell-text"
                    $upsellElements = $xpath->query('.//div[@class="upsell-text"]', $serviceListDiv);
                    foreach ($upsellElements as $upsell) {
                        $upsell->parentNode->removeChild($upsell);
                    }

                    // Remove divs with class "ticket-upsell" (using contains to match multiple classes)
                    $ticketUpsellElements = $xpath->query('.//a[contains(@class, "ticket-upsell")]', $serviceListDiv);
                    foreach ($ticketUpsellElements as $ticketUpsell) {
                        $ticketUpsell->parentNode->removeChild($ticketUpsell);
                    }

//                    // Add <hr> after each <a class="service"> element
//                    $serviceLinks = $xpath->query('.//a[contains(@class, "service")]', $serviceListDiv);
//                    foreach ($serviceLinks as $serviceLink) {
//                        $hr = $dom->createElement('hr');
//                        $serviceLink->parentNode->insertBefore($hr, $serviceLink->nextSibling);
//                    }

                    $trainTimesHtml = $dom->saveHTML($serviceListDiv);
                }

                return response()->json([
                    'success' => true,
                    'data' => $trainTimesHtml
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to fetch train times data.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
