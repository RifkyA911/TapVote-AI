<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    /**
     * Display the comprehensive interactive system documentation.
     */
    public function index()
    {
        return view('admin.docs.index');
    }
}
