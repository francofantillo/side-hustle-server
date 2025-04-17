<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->String('user_id')->nullable();           
            $table->String('payer_email')->nullable();            
            $table->String('stripe_subscription_id')->nullable();
            $table->String('stripe_customer_id')->nullable();
            $table->String('stripe_plan_id')->nullable();
            $table->Double('plan_amount')->nullable();
            $table->String('plan_amount_currency')->nullable();
            $table->String('plan_interval')->nullable();
            $table->DateTime('plan_period_start')->nullable();
            $table->DateTime('plan_period_end')->nullable();
            $table->String('payment_method')->nullable();
            $table->String('status')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
