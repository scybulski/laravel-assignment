<?php

namespace App\Http\Controllers;

use App\Facades\Contracts\MovieContract;
use App\Http\Resources\MovieCollection;
use App\Http\Responses\StatusResponse;
use Exception;

class MovieController extends Controller
{
    public function __construct(
        protected MovieContract $movieService,
    ) {
    }

    public function getTitles(): MovieCollection|StatusResponse
    {
        try {
            $response = $this->movieService->getTitles();

            return MovieCollection::make($response);
        } catch (Exception $e) {
            return StatusResponse::make(
                httpStatus: StatusResponse::HTTP_INTERNAL_SERVER_ERROR,
                status: StatusResponse::STATUS_FAILURE,
            );
        }
    }
}
