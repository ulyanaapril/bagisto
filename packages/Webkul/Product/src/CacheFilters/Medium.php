<?php

namespace Webkul\Product\CacheFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Medium implements FilterInterface
{
    /**
     * @param  \Intervention\Image\Image  $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {

        $image->resize(280, 350, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $image->resizeCanvas(300, 300, 'center', false, '#fff');

    }
}