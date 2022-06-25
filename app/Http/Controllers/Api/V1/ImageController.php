<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(ImageController $request)
    {
        $file = $request->file('image');
        $name = Str::random(10);
        $url = \Storage::putFileAs('images', $file, $name . '.' . $file->extension());

        return [
          'url' => env('APP_URL') . '/' . $url
        ];
    }
}
