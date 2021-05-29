<?php

namespace App\Http\Requests\Api;

/**
 * Validate the budget income stats request
 *
 * @package App\Http\Requests\Api
 */
class BudgetStatsRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'incomes' => 'sometimes|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'incomes' => $this->setBoolean($this->incomes ?? null),
        ]);
    }

    /**
     * Convert to a definite boolean
     *
     * @param $value
     *
     * @return bool
     */
    protected function setBoolean($value): bool
    {
        if ($value === true || $value === 'true' || $value === 1 || $value === '1' || $value === 'yes') {
            $value = true;
        } else {
            $value = false;
        }

        return $value ? '1' : '0';
    }
}
