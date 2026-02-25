<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Todo;

abstract class Controller
{
    use AuthorizesRequests;
}
