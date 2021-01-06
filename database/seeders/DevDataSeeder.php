<?php

namespace Database\Seeders;

use App\Facades\Services\AuthService;
use App\Models\Budget;
use App\Models\BudgetGroup;
use App\Models\Category;
use App\Models\BudgetEntry;
use App\Models\Group;
use App\Models\BudgetIncome;
use App\Models\Site;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Class DevDataSeeder
 * @package Database\Seeders
 */
class DevDataSeeder extends Seeder
{
    /**
     * @var Site
     */
    protected $site;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Budget
     */
    protected $budget;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Site
        $this->site = Site::create(['name' => 'Family Budget']);

        // Add some nested categories
        $this->addCategories();

        // Create Password : Double Hashed
        // Password is: Testing01


//        $password = 'Testing01';
//        $password = hash('sha256', $password);
//        $password = UserService::hashPassword($password);
        $password = AuthService::hashPassword('Testing01', true);

        // Create user
        $this->user = User::create([
            'siteId' => $this->site->id,
            'email' => 'dave@unitiweb.com',
            'password' => $password,
            'firstName' => 'Dave',
            'lastName' => 'Torres',
            'role' => User::ROLE_USER,
            'verified' => true,
            'status' => User::STATUS_ACTIVE,
        ]);

        // Create budget
        $this->budget = Budget::create([
            'siteId' => $this->site->id,
            'month' => Carbon::now()->startOfMonth(),
        ]);

        // Create incomes
        $income1 = BudgetIncome::create([
            'siteId' => $this->site->id,
            'budgetId' => $this->budget->id,
            'name' => '1st Payday',
            'dueDay' => (Carbon::now())->day,
            'amount' => 1000.00,
        ]);

        $income2 = BudgetIncome::create([
            'siteId' => $this->site->id,
            'budgetId' => $this->budget->id,
            'name' => '2nd Payday',
            'dueDay' => (Carbon::now())->day,
            'amount' => 1500.00,
        ]);

        // Create groups
        $group1 = Group::create([
            'siteId' => $this->site->id,
            'name' => 'Utilities',
            'order' => 1,
        ]);

        $group2 = Group::create([
            'siteId' => $this->site->id,
            'name' => 'Loans',
            'order' => 2,
        ]);

        $group3 = Group::create([
            'siteId' => $this->site->id,
            'name' => 'Entertainment',
            'order' => 3,
        ]);

        // Create budget groups
        $budgetGroup1 = BudgetGroup::create([
            'siteId' => $this->site->id,
            'budgetId' => $this->budget->id,
            'groupId' => $group1->id,
            'name' => 'Utilities',
            'order' => 1,
        ]);

        $budgetGroup2 = BudgetGroup::create([
            'siteId' => $this->site->id,
            'budgetId' => $this->budget->id,
            'groupId' => $group2->id,
            'name' => 'Loans',
            'order' => 2,
        ]);

        $budgetGroup3 = BudgetGroup::create([
            'siteId' => $this->site->id,
            'budgetId' => $this->budget->id,
            'groupId' => $group3->id,
            'name' => 'Entertainment',
            'order' => 3,
        ]);

        // Create some entries
        $this->entry('Washington Power', $budgetGroup1, $income1, 11, 231.89);
        $this->entry('Verizon', $budgetGroup1, $income1, 20, 205.99);
        $this->entry('Questar Gas', $budgetGroup1, $income1, 30, 31.00);

        $this->entry('Kia Loan', $budgetGroup2, $income2, 10, 331.00);
        $this->entry('Boat Loan', $budgetGroup2, $income2, 15, 200.00);

        $this->entry('NetFlix', $budgetGroup3, $income1, 5, 15.25);
        $this->entry('Hulu', $budgetGroup3, $income2, 23, 78.50);
    }

    /**
     * Create an entry
     *
     * @param string $name
     * @param BudgetGroup $group
     * @param BudgetIncome $income
     * @param int $day
     * @param float $amount
     *
     * @return BudgetEntry
     */
    protected function entry(string $name, BudgetGroup $group, BudgetIncome $income, int $day, float $amount): BudgetEntry
    {
        return BudgetEntry::create([
            'siteId' => $this->site->id,
            'budgetId' => $this->budget->id,
            'budgetGroupId' => $group->id,
            'budgetIncomeId' => $income->id,
            'name' => $name,
            'dueDay' => $day,
            'amount' => $amount,
            'autoPay' => false,
            'goal' => false,
            'paid' => false,
            'cleared' => false,
        ]);
    }

    protected function addCategories()
    {
        // id, parentId, name
        $categories = [
            [1, NULL, 'Food and Drink'],
            [2, 1, 'Restaurants'],
            [3, 2, 'Fast Food'],
            [4, NULL, 'Travel'],
            [5, 4, 'Taxi'],
            [6, NULL, 'Payment'],
            [7, NULL, 'Shops'],
            [8, 7, 'Sporting Goods'],
            [9, 6, 'Credit Card'],
            [10, NULL, 'Transfer'],
            [11, 10, 'Debit'],
            [12, 10, 'Deposit'],
            [13, NULL, 'Recreation'],
            [14, 13, 'Gyms and Fitness Centers'],
            [15, 4, 'Airlines and Aviation Services'],
            [16, 2, 'Coffee Shop'],
            [17, 10, 'Credit'],
        ];

        foreach ($categories as $category) {
            $cat = new Category;
            $cat->siteId = $this->site->id;
            $cat->id = $category[0];
            $cat->parentId = $category[1];
            $cat->name = $category[2];
            $cat->save();
        }
    }
}
