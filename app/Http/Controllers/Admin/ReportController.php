<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function harian()
    {
        return view('report.harian');
    }

    public function bulanan()
    {
        return view('report.bulanan');
    }
}
