<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $data = [
            ['id' => 1, 'name' => '鈴木イチロウ', 'description' => 'Description for 鈴木イチロウ'],
            ['id' => 2, 'name' => '山田タロウ', 'description' => 'Description for 山田タロウ'],
            ['id' => 3, 'name' => '伊藤シンジ', 'description' => 'Description for 伊藤シンジ'],
        ];

        return response()->json($data);
    }
}
