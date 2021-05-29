<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\BudgetIncome;
use Illuminate\Support\Collection;

/**
 * Perform tasks related to budget stats
 *
 * @package App\Services
 */
class BudgetStatsService
{
    /**
     * Calculate the budget totals stats
     *
     * @param Budget $budget
     *
     * @return Collection
     */
    public function totals(Budget $budget): Collection
    {

        return collect();
    }

    /**
     * Calculate the budge income's stats can calculated amounts
     *
     * @param Budget $budget
     *
     * @return Collection
     */
    public function incomes(Budget $budget): Collection
    {
        $today = now()->day;
        $subtotals = collect([
            'amount' => 0,
            'expenses' => 0,
            'outStanding' => 0,
            'leftOver' => 0,
        ]);

        // Closure to add to subtotals with the given key and income
        $add = fn (string $key, BudgetIncome $income) => $subtotals->put($key, $subtotals->get($key) + $income->$key);

        // Closure to calculate the percent
        $percent = fn (float $small, float $large) => round(($small / $large) * 100);

        $incomes = BudgetIncome::with('entries')
            ->where('budgetId', $budget->id)
            ->get()
            ->map(function (BudgetIncome $income) use ($today, $subtotals, $add) {
                $income->setAttribute('expenses', 0);
                $income->setAttribute('outStanding', 0);
                $income->setAttribute('leftOver', 0);
                foreach ($income->entries as $entry) {
                    $amount = round($entry->amount, 2);
                    $income->expenses += $amount;
                    if (!$entry->cleared) {
                        $income->outStanding += $amount;
                    }
                }

                if ($income->dueDay <= $today) {
                    $add('amount', $income);
                    $add('expenses', $income);
                    $add('outStanding', $income);
                }

                return collect([
                    'id' => $income->id,
                    'name' => $income->name,
                    'amount' => round($income->amount, 2),
                    'expenses' => round($income->expenses, 2),
                    'outStanding' => round($income->outStanding, 2),
                    'leftOver' => round($income->amount - $income->expenses, 2),
                ]);
            })
            ->toBase();

        $subtotals->put(
            'leftOver',
            round($subtotals->get('amount') - $subtotals->get('expenses'), 2),
        );

        $amount = $incomes->sum('amount');
        $expenses = $incomes->sum('expenses');
        $outStanding = $incomes->sum('outStanding');
        $leftOver = $incomes->sum('leftOver');

        return collect([
            'totals' => collect([
                'amount' => $amount,
                'expenses' => $expenses,
                'outStanding' => $outStanding,
                'leftOver' => $leftOver,
            ]),
            'subTotals' => $subtotals,
            'percents' => [
                'amount' => $percent($subtotals->get('amount'), $amount),
                'expenses' => $percent($subtotals->get('expenses'), $expenses),
                'outStanding' => $percent($subtotals->get('outStanding'), $outStanding),
                'leftOver' => $percent($subtotals->get('leftOver'), $leftOver),
            ],
            'incomes' => $incomes,
        ]);
    }
}
