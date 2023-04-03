<?php

namespace Tests\Feature;

use App\Constants\LoanStatus;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    private string $apiRoute = 'api/v1/loans';

    public function testLoanDetailsFetchedSuccessfully()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'email' => 'npatuan.uit@gmail.com',
            'password' => bcrypt('password1'),
            'role' => 'admin'
        ]);
        $this->actingAs($user, 'api');

        $loanData = [
            'user_id' => 1,
            'term' => '3',
            'amount' => 10000,
        ];

        $loan = Loan::factory()->create($loanData);

        $this->json('GET', "$this->apiRoute/$loan->id", [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                'loan' => $loan->toArray(),
                'message' => 'Fetched a loan successfully'
            ]);
    }
}
