<?php

namespace App\Http\Controllers\Api\Financial;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Financial\BankInstitutionResource;
use App\Models\BankInstitution;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class InstitutionController
 * @package App\Http\Controllers\Financial
 */
class InstitutionController extends ApiController
{
    /**
     * Get all the financial accounts
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $with = $this->getWith(['accounts']);
        $institutions = BankInstitution::with($with->toArray())->get();

        return BankInstitutionResource::collection($institutions);
    }

    /**
     * Get a single bank institution
     *
     * @param BankInstitution $institution
     *
     * @return BankInstitutionResource
     */
    public function show(BankInstitution $institution): BankInstitutionResource
    {
        $with = $this->getWith(['accounts']);
        $institution->load($with->toArray());

        return new BankInstitutionResource($institution);
    }
}
