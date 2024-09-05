<?php

namespace App\Http\Resources;

use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieCollection extends JsonResource
{
    public function __construct(
        protected MovieCollectionDto $movieCollectionDto,
    ) {
    }

    public function toArray(Request $request): array
    {
        return array_map(
            fn (MovieDto $movieDto) => [
                'title' => $movieDto->title,
            ],
            $this->movieCollectionDto->movies,
        );
    }
}
