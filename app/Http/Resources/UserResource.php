<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     /**
     *  @OA\Schema(
     *    schema="UserSchema",
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
     *                          property="password",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="setting",
     *                          type="object",
     *                          @OA\Property(property="role_id", type="number"),
     *                          @OA\Property(property="team_leader_id", type="number"),
     *                          @OA\Property(property="underwriter_id", type="number"),
     *                          @OA\Property(property="is_underwriter", type="number"),
     *                          @OA\Property(property="is_round_robin", type="number"),
     *                          @OA\Property(property="agent_type", type="number"),
     *                          @OA\Property(property="renewal_deals", type="number"),
     *                          @OA\Property(property="insurance_type", type="number"),
     *                          @OA\Property(property="status", type="number"),
     *                      )
     *                 ),
     * ),
      */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
