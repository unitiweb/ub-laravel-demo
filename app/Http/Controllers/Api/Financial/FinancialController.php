<?php

namespace App\Http\Controllers\Api\Financial;

use App\Facades\Services\AuthService;
use App\Financials\Financial;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Financial\BankLinkTokenResource;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class FinancialController extends ApiController
{
    /**
     * Create a link token
     *
     * @param Financial $financial
     *
     * @return BankLinkTokenResource
     */
    public function linkToken(Financial $financial): BankLinkTokenResource
    {
        $siteId = AuthService::getSite()->id;

        $linkToken = $financial->createLinkToken($siteId);

        return new BankLinkTokenResource($linkToken);
    }

    /**
     * Exchange the public token for an access token and store it in the database
     *
     * @param Request $request
     * @param Financial $financial
     *
     * @return Response
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function exchangePublicToken(Request $request, Financial $financial): Response
    {
        $data = $this->validate($request, [
            'publicToken' => 'required|string'
        ]);

        $siteId = AuthService::getSite()->id;

        if (!$accessToken = $financial->createAccessToken($data['publicToken'], $siteId)) {
            throw new Exception('The access token could not be created');
//            throw new JsonError(
//                'The access token could not be created',
//                Response::HTTP_INTERNAL_SERVER_ERROR
//            );
        }

        return response()->noContent();
    }
}
