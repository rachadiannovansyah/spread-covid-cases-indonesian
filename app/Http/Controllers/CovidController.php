<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Charts\CovidChart;


class CovidController extends Controller
{
    // chart functionality
    public function chart()
    {
        // hit api endpoint
        $covidCase = collect(Http::get('https://data.covid19.go.id/public/api/prov.json')->json()['list_data'])->sortByDesc('jumlah_kasus');
        $labelCovid = $covidCase->pluck('key');
        $dataCovid = $covidCase->pluck('jumlah_kasus');
        $colors = $labelCovid->map(function ($item) {
            return '#' . substr(md5(mt_rand()), 0, 6);
        });
        // dd($labelCovid, $dataCovid);
        // Sebaran kasus covid19
        $chart = new CovidChart;
        $chart->labels($labelCovid);
        $chart->dataset('Data Kasus COVID-19 Indonesia', 'pie', $dataCovid)->backgroundColor($colors);

        return view('covid', ['chart' => $chart]);
    }
}
