<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\WebsiteInterface;
use Illuminate\Support\Facades\Log;
use App\Traits\ResponseTrait;

class WebsiteController extends Controller
{
    use ResponseTrait;

    public $websiteInterface;

    public function __construct(WebsiteInterface $websiteInterface)
    {
        $this->websiteInterface = $websiteInterface;
    }

    /**
     * @OA\Get(
     *     path="/public/api/device",
     *     summary="Create new device id and generate new token",
     *      tags={"Public Website"},
     *      @OA\Parameter(
     *          name="country_product_id",
     *          in="query",
     *          description="country where it is located, default 1",
     *          required=true, 
     *      ),
     *      @OA\Parameter(
     *          name="device_type",
     *          in="query",
     *          description="device type, default 1",
     *          required=true, 
     *      ),
     *      @OA\Parameter(
     *          name="source_id",
     *          in="query",
     *          description="source type, default 1",
     *          required=true, 
     *      ),
     *      @OA\Parameter(
     *          name="utm_campaign",
     *          in="query",
     *          description="default organic",
     *          required=false, 
     *      ),
     *      @OA\Parameter(
     *          name="device_uuid",
     *          in="query",
     *          description="id that will be use to map from frontend",
     *          required=false, 
     *      ),
     *      @OA\Parameter(
     *          name="utm_medium",
     *          in="query",
     *          description="default organic",
     *          required=false, 
     *      ),
     *      @OA\Parameter(
     *          name="utm_source",
     *          in="query",
     *          description="default organic",
     *          required=false, 
     *      ),
     *      @OA\Parameter(
     *          name="utm_term",
     *          in="query",
     *          description="default organic",
     *          required=false, 
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Device id created"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="stnumberring", example=1),
     *                  @OA\Property(property="country_product_id", type="stnumberring", example=1),
     *                  @OA\Property(property="device_type", type="stnumberring", example=1),
     *                  @OA\Property(property="source_id", type="stnumberring", example=1),
     *                  @OA\Property(property="utm_campaign", type="string", example="organic"),
     *                  @OA\Property(property="utm_medium", type="string", example="organic"),
     *                  @OA\Property(property="utm_source", type="string", example="organic"),
     *                  @OA\Property(property="utm_term", type="string", example="organic"),
     *                  @OA\Property(property="device_uuid", type="string", example="pH4oCGmb-j2uwr-0GMsC-15RNu-HNzta942mA2w"),
     *                  @OA\Property(property="token_expires", type="string", example=1708683278),
     *                  @OA\Property(property="refresh_expires", type="string", example=1708683293),
     *              ),
     *          )
     *      )
     * )
     */
    public function createDevice(Request $request) {
        $device = $this->websiteInterface->device($request->all());
        $token = $device['auth'];
        unset($device['auth']);


        return $this->resSuccess('Device id created', $device)
            ->cookie('auth', $token, 900, '/', '.personal.me', true);
    }
}
