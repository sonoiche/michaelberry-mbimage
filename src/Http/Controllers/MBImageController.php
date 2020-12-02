<?php

namespace MichaelBerry\MBImage\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use MichaelBerry\MBImage\Models\MBImage;

class MBImageController extends Controller
{
    public function index()
    {
        $data['images'] = MBImage::orderBy('id', 'desc')->first();
        return view('mbimage::index', $data);
    }

    public function store(Request $request)
    {
        $field = $request->file('image');
        $hasfile = $request->hasFile('image');
        $path = config('mbimage.path');
        $imageurl = $this->fileUpload($path, $field, $hasfile, '', config('mbimage.type'));
        MBImage::create([
            'thumbnail_size' => $imageurl['thumbnail'],
            'small_size' => $imageurl['small'],
            'full_size' => $imageurl['full']
        ]);

        return redirect()->back();
    }

    private function fileUpload($path, $field, $hasfile, $default = '', $type = ''){
        $imgproperties = getimagesize($field);
        $thumbwidth = ($imgproperties[1] / 4);
        $smallwidth = ($imgproperties[1] / 2);
        $thumbheight = ($imgproperties[0] / $imgproperties[1]) * $thumbwidth;
        $smallheight = ($imgproperties[0] / $imgproperties[1]) * $smallwidth;
        switch ($type) {
            case 'S3':
                if($hasfile){
                    if($field->isValid()){
                        $filename = $field->getClientOriginalName();
                        $extension = $field->getClientOriginalExtension();
                        $file = $path.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_full.'.$extension;
                        $thumbnail = Image::make($field->getRealPath())->resize($thumbheight, $thumbwidth);
                        $imgthumb = $thumbnail->stream();
                        Storage::disk('s3')->put($path.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_thumbnail.'.$extension, $imgthumb->__toString(), 'public');

                        $small = Image::make($field->getRealPath())->resize($smallheight, $smallwidth);
                        $imgsmall = $small->stream();

                        $full = Image::make($field->getRealPath());
                        $imgfull = $full->stream();
                        Storage::disk('s3')->put($path.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_small.'.$extension, $imgsmall->__toString(), 'public');
                        Storage::disk('s3')->put($path.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_full.'.$extension, $imgfull->__toString(), 'public');

                        return [
                            'thumbnail' => Storage::cloud()->url($path.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_thumbnail.'.$extension),
                            'small' => Storage::cloud()->url($path.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_small.'.$extension),
                            'full' => Storage::cloud()->url($path.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_full.'.$extension)
                        ];
                    }
                } else {
                    $file = $default;
                }
                return isset($file) ? $file : '';
            break;
            
            default:
            
            $destinationPath = public_path().'/'.$path;
            if($hasfile){
                if($field->isValid()){
                    $filename = $field->getClientOriginalName();
                    $extension = $field->getClientOriginalExtension();
                    $file = $destinationPath.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_full.'.$extension;
                    $thumbnail = Image::make($field->getRealPath())->resize($thumbheight, $thumbwidth);
                    $thumbnail->save($destinationPath.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_thumbnail.'.$extension, 100);

                    $small = Image::make($field->getRealPath())->resize($smallheight, $smallwidth);
                    $small->save($destinationPath.'/'.pathinfo(str_replace(' ', '_', $filename), PATHINFO_FILENAME).'_small.'.$extension, 100);
                    
                    $field->move($destinationPath, $file);
                }
            } else {
                $file = $default;
            }
            return isset($file) ? $file : '';

            break;
        }
    }

}
