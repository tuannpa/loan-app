<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Customer;
use App\Models\Loan;

class CreateRepaymentsTable extends Migration
{
    private string $table = 'repayments';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Customer::class);
                $table->foreignIdFor(Loan::class);
                $table->string('state');
                $table->integer('payment_order');
                $table->dateTime('repaid_date');
                $table->integer('amount');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
