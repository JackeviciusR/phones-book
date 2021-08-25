<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'contacts';
        Schema::create('' . $table . '', function (Blueprint $table) {
            $table->id();
            $table->string('name', '36')->nullable(false);
            $table->string('phone', '30')->nullable(false);

            $table->unsignedBigInteger('creator_id')->nullable(true);
            $table->foreign('creator_id')->references('id')->on('users');

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
        Schema::dropIfExists('contacts');
    }
}
