<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeatmapController extends Controller
{
    public function index()
    {
        // Data heatmap (untuk layer heatmap)
        $data = [
            ['lat' => -6.4885, 'lon' => 107.6838, 'weight' => 2],
            ['lat' => -6.49, 'lon' => 107.69, 'weight' => 1.5],
            ['lat' => -6.495, 'lon' => 107.675, 'weight' => 2.5],
        ];

        // Data marker (untuk marker di peta)
        $initialMarkers = [
            [
                'position' => [
                    'lat' => -6.4885,
                    'lng' => 107.6838
                ],
                'draggable' => false
            ],
            [
                'position' => [
                    'lat' => -6.49,
                    'lng' => 107.69
                ],
                'draggable' => false
            ],
            [
                'position' => [
                    'lat' => -6.495,
                    'lng' => 107.675
                ],
                'draggable' => true
            ]
        ];

        // Konversi data ke format yang sesuai dengan Leaflet.heat
        $heatmapData = array_map(function($point) {
            return [$point['lat'], $point['lon'], $point['weight']];
        }, $data);

        return view('pages.heatmap.index', compact('heatmapData', 'initialMarkers'));
    }
}
