<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UploadImages
{




       //  Upload a single image for a user, save it in a specific folder, and return the image name
    public function uploadImage($image, $username, $folder = 'frontend/img/user', $oldImageName = null)
    {
        //  Check if image exists and is valid
        if ($image && $image->isValid()) {

            // Delete old image if provided and exists
            if ($oldImageName) {
                $this->deleteUserImage($oldImageName);
            }

            //  Generate a unique image name using current time and username
            $imageName = time() . '_' . Str::slug($username) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path($folder);

            //  Create the destination folder if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            //  Move the uploaded image to the destination folder
            $image->move($destinationPath, $imageName);

            //  Return the image name for storage or reference
            return $imageName;
        }

        //  Return null if image is not valid
        return null;
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //  Upload multiple images for a post and create image records in the database
    public function uploadImages($images, $post)
    {
        //  Check if there are images to upload
        if ($images && count($images) > 0) {
            $i = 1;
            foreach ($images as $image) {
                //  Generate a unique file name for each image
                $file_name = Str::slug($post->title) . '_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                $path = 'test/' . $file_name;
                //  Move the image to the test folder
                $image->move(public_path('/frontend/img/test'), $file_name);
                //  Create a new image record associated with the post
                $post->images()->create([
                    'path' => $path,
                ]);
                $i++;
            }
        }
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //  Remove one or more images from the filesystem and database
    public function removeImage($images)
    {
        //  Normalize input to an array or collection
        $images = is_array($images) || $images instanceof \Illuminate\Support\Collection ? $images : [$images];

        foreach ($images as $image) {
            //  Build the full path to the image file
            $imagePath = public_path('frontend/img/' . $image->path);
            //  Delete the image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            //  Delete the image record from the database
            $image->delete();
        }
    }

    public function deleteUserImage($image)
    {
        //  Build the full path to the image file
        $imagePath = public_path('frontend/img/user/' . $image);
        //  Delete the image file if it exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

}
