<?php

namespace App\Http\Controllers;

use App\Dto\ReserveDto;
use App\Http\Requests\ReserveRequest;
use App\Http\Resources\ReservationResource;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Response;

class ReservationController extends Controller
{
   public function __construct(
      private readonly ReservationService $reservationService
   ) {}
   public function reserve(ReserveRequest $reserveRequest)
   {
      $data = $reserveRequest->validated();
      $reservation = $this->reservationService->reserve(new ReserveDto(
         $data['room_type_id'],
         $data['requested_count'],
         $data['date_to_reserve']
      ));

      return Response::gen(
         ReservationResource::make($reservation)
      );
   }


}
