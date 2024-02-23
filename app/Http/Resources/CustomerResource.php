<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     /**
     *  @OA\Schema(
     *    schema="CustomerQLSchema",
     *                  @OA\Property(
     *                      type="object",
     *                      
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="country_code",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone_number",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="other_contact_info",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="insurance_type",
     *                          type="number"
     *                      )
     *                 ),
     * ),
      */

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
