<?php

namespace App\Controller;

use App\Entity\Picture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreatePictureAction
{
    public function __invoke(Request $request): Picture
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $picture = new Picture();
        $picture->setFile($uploadedFile) ;

        return $picture;
    }
}