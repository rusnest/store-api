<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users', function (Blueprint $table) {
                $table->uuid('id');
                $table->string('full_name')->nullable();
                $table->string('display_name')->nullable();
                $table->string('email')->unique()->nullable();;
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();;
                $table->string('phone_number')->nullable();
                $table->string('profile_image')->nullable();
                $table->string('google_id')->nullable();
                $table->primary('id');
                $table->rememberToken();
                $table->softDeletes();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
