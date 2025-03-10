<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $data = [
            ['id' => 1, 'name' => '鈴木イチロウ', 'date' => '2025-03-01 10:00:00', 'description' => 'Description for 鈴木イチロウ'],
            ['id' => 2, 'name' => '山田タロウ', 'date' => '2025-03-02 09:00:00', 'description' => 'Description for 山田タロウ'],
            ['id' => 3, 'name' => '伊藤シンジ', 'date' => '2025-03-03 11:00:00', 'description' => 'Description for 伊藤シンジ'],
        ];

        return response()->json($data);
    }
}
