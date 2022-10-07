<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name'); 
            $table->string('email')->unique();
            $table->date('tgl_lahir')->nullable(); 
            $table->string('tpt_lahir')->nullable();  
            $table->string('password');
            $table->string('picture')->nullable();
            $table->string('nip')->nullable();
            $table->string('no_tlp')->nullable();
            $table->string('user_name')->nullable();
            $table->string('token')->nullable();
            $table->timestamp('token_expired')->nullable();
            $table->timestamps();
        });
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
}
