<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;

class CustomSlugger 
{
    private $sluggerInterface;

    /**
    * Constructor
    */
    public function __construct(SluggerInterface $sluggerInterface)
    {
        $this->sluggerInterface = $sluggerInterface;
    }

    /**
     * renvoit le slug à partir d'une string
     *
     * @param string $stringToSlug la string à transformer
     * @return string le slug de la string
     */
    public function slugToLower(string $stringToSlug): string
    {
        $slug = $this->sluggerInterface->slug($stringToSlug)->lower();

        return $slug;
    }
}