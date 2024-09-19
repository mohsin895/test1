<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use stdClass;

trait MediaTraits
{
    public function std(): stdClass
    {
        return new stdClass;
    }

    private function trimSlash($str)
    {
        $trim = trim($str, '/');
        if ($trim) {
            $trim .= '/';
        }

        return '';
    }
    public function saveMedia($file, $opt = [])
    {
        $res = $this->std();
        $res->status = false;
        $res->message = '';
        $options = new stdClass;

        $options->dir = isset($opt['dir']) ? $opt['dir'] : '';
        $options->type = isset($opt['type']) ? $opt['type'] : 'webp';
        $options->quality = isset($opt['quality']) ? $opt['quality'] : 80;

        try {
            // Check if the file is already in WebP format
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            if ($extension === 'webp') {
                // Save the file directly
                $base_path = 'uploads/path/' . date('Y-m') . '/' . $this->trimSlash($options->dir);
                $base_real_path = 'uploads/real-path/' . date('Y-m') . '/' . $this->trimSlash($options->dir);

                if (!is_dir($base_path)) {
                    File::makeDirectory($base_path, 493, true);
                }
                if (!is_dir($base_real_path)) {
                    File::makeDirectory($base_real_path, 493, true);
                }

                $file_name = time() . rand(100, 999) . '.' . $extension;

                // Save original file
                $file->move(public_path($base_real_path), $file_name);
                // $res->path = $base_path . $file_name;
                $res->path = $base_real_path . $file_name;
                $res->real_path = $base_real_path . $file_name;

                $media = Media::create([
                    'path' => $res->path,
                    'real_path' => $res->real_path,
                    'user_id' => auth()->id(),
                ]);

                if (isset($opt['delete']) && $opt['delete']) {
                    $this->deleteMedia($opt['delete']);
                }

                $res->id = $media->id;
                $res->status = true;
            } else {
                // Process and save the image
                $img = Image::make($file);

                $base_path = 'uploads/path/' . date('Y-m') . '/' . $this->trimSlash($options->dir);
                $base_real_path = 'uploads/real-path/' . date('Y-m') . '/' . $this->trimSlash($options->dir);

                if (!is_dir($base_path)) {
                    File::makeDirectory($base_path, 493, true);
                }
                if (!is_dir($base_real_path)) {
                    File::makeDirectory($base_real_path, 493, true);
                }

                $file_name = time() . rand(100, 999) . '.' . $options->type;

                // Save original file
                $img->save(public_path($base_real_path . $file_name));

                // Save optimized image
                $img->encode($options->type, $options->quality);
                $img->save(public_path($base_path . $file_name));

                $res->path = $base_path . $file_name;
                $res->real_path = $base_real_path . $file_name;

                $media = Media::create([
                    'path' => $res->path,
                    'real_path' => $res->real_path,
                    'user_id' => auth()->id(),
                ]);

                if (isset($opt['delete']) && $opt['delete']) {
                    $this->deleteMedia($opt['delete']);
                }

                $res->id = $media->id;
                $res->status = true;
            }
        } catch (\Throwable $th) {
            $res->message = $th->getMessage();
            saveLog($res->message, 'image-trait.log');
        }

        return $res;
    }


    // public function saveMedia($file, $opt = [])
    // {
    //     $res = $this->std();
    //     $res->status = false;
    //     $res->message = '';
    //     $options = new stdClass;

    //     $options->dir = isset($opt['dir']) ? $opt['dir'] : '';
    //     $options->type = isset($opt['type']) ? $opt['type'] : 'webp';
    //     $options->quality = isset($opt['quality']) ? $opt['quality'] : 80;

    //     try {
    //         $img = Image::make($file);

    //         $base_path = 'uploads/path/' . date('Y-m') . '/' . $this->trimSlash($options->dir);
    //         $base_real_path = 'uploads/real-path/' . date('Y-m') . '/' . $this->trimSlash($options->dir);

    //         if (!is_dir($base_path)) {
    //             File::makeDirectory($base_path, 493, true);
    //         }
    //         if (!is_dir($base_real_path)) {
    //             File::makeDirectory($base_real_path, 493, true);
    //         }

    //         $file_name = time() . rand(100, 999) . '.' . $options->type;

    //         // save original file
    //         $img->save(public_path($base_real_path . $file_name));

    //         // save optimized image
    //         $img->encode($options->type, $options->quality);
    //         $img->save(public_path($base_path . $file_name));

    //         $res->path = $base_path . $file_name;
    //         $res->real_path = $base_real_path . $file_name;

    //         $media = Media::create([
    //             'path' => $res->path,
    //             'real_path' => $res->real_path,
    //             'user_id' => auth()->id(),
    //         ]);

    //         if (isset($opt['delete']) && $opt['delete']) {
    //             $this->deleteMedia($opt['delete']);
    //         }

    //         $res->id = $media->id;
    //         $res->status = true;
    //     } catch (\Throwable $th) {
    //         $res->message = $th->getMessage();
    //     }

    //     return $res;
    // }

    public function uploadFile($file, $opt = [])
    {
        $res = $this->std();
        $res->status = false;
        $res->message = '';
        $options = new stdClass;

        $options->dir = isset($opt['dir']) ? $opt['dir'] : '';

        try {

            $base_path = 'uploads/' . date('Y-m') . '/' . $this->trimSlash($options->dir);

            if (!is_dir($base_path)) {
                File::makeDirectory($base_path, 493, true);
            }

            $file_name = time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();

            $file->move($base_path, $file_name);

            $res->path = $base_path . $file_name;
            $res->real_path = $base_path . $file_name;

            $media = Media::create([
                'path' => $res->path,
                'real_path' => $res->real_path,
                'user_id' => auth()->id(),
            ]);

            if (isset($opt['delete']) && $opt['delete']) {
                $this->deleteMedia($opt['delete']);
            }

            $res->id = $media->id;
            $res->status = true;
        } catch (\Throwable $th) {
            $res->message = $th->getMessage();
        }

        return $res;
    }

    /**
     * @param $id
     * find media by id and delete it with it's linked file
     */
    public function deleteMedia($id): stdClass
    {
        $res = $this->std();
        $res->status = false;
        $media = Media::find($id);

        if ($media) {
            try {
                DB::beginTransaction();
                File::delete($media->path);
                File::delete($media->real_path);
                $media->delete();
                DB::commit();
                $res->status = true;
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }

        return $res;
    }

    public function uploadImage(Request $request, $input, $dir, $type = 'webp')
    {
        if ($request->hasFile($input)) {
            $file = $request->file($input);
            $img = Image::make($file);
            $img->encode($type, 80);

            $path = 'uploads/' . $dir . '/' . time() . '.' . $type;
            $img->save(public_path($path));

            return $path;
        }

        return false;
    }
}
