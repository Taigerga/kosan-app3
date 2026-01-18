<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Halaman Tentang Kami
    public function about()
    {
        return view('public.pages.about');
    }

    // Halaman Cara Memesan
    public function howto()
    {
        return view('public.pages.howto');
    }

    // Halaman Syarat & Ketentuan
    public function terms()
    {
        return view('public.pages.terms');
    }

    // Halaman Kebijakan Privasi
    public function privacy()
    {
        return view('public.pages.privacy');
    }
}