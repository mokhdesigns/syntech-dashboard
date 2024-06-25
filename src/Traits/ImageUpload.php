<?php

namespace Syntech\Dashboard\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
trait ImageUpload
{

    public function ImageUpload($query)
    {
        $image_name = str::random(20);

        $ext = strtolower($query->getClientOriginalExtension());

        $image_full_name = $image_name.'.'.$ext;

        $upload_path = 'images/';

        $image_url = asset($upload_path.$image_full_name);

        $query->move($upload_path,$image_full_name);

        return $image_url;
    }


    public function GallryUpload($gallery, $folder = 'images')
    {

        $images = [];

        foreach($gallery as $index => $file){

            $fileName = $file->getClientOriginalExtension();

            $upload_path =  $folder . '/';

            $fileName = uniqid() .  'buildin.' . $fileName;

            $file->move('images/',$fileName);

            $image_url = asset($upload_path.$fileName);

            $images[] = $image_url;
        }

        if(count($images) == 0) return null;


        return  implode(",",$images);

    }

     public function FileUpload($file, $customerId){

            $fileName = $file->getClientOriginalExtension();

            $upload_path = 'file/' . $customerId . '/';

            $fileName = uniqid() .  'buildin.' . $fileName;

            $file->move('file/' . $customerId . '/',$fileName);

            $file_url = asset('file/' . $customerId .$fileName);

            return $file_url;
     }

     public function CVUpload($query, $name){


        $name = str_replace(' ', '', $name);

        $image_name = $name . '-' . time();

        $ext = strtolower($query->getClientOriginalExtension());

        $image_full_name = $image_name.'.'.$ext;

        $upload_path = 'cv/';

        $image_url = asset($upload_path.$image_full_name);

        $query->move($upload_path,$image_full_name);

        return $image_url;

     }


     public function uploadResult($query, $id){

            $image_name = str::random(20);

            $ext = strtolower($query->getClientOriginalExtension());

            $image_full_name = $image_name.'.'.$ext;

            $upload_path = 'results/' . $id . '/';

            $image_url = asset($upload_path.$image_full_name);

            $query->move($upload_path,$image_full_name);

            return $image_url;
    }
}


