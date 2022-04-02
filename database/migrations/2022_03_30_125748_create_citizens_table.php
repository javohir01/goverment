<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('f_name', 30);
            $table->string('l_name', 30);
            $table->string('m_name', 30);

            $table->string('passport', 9)->unique();
            $table->string('pin',14);
            $table->date('birth_date')->nullable();

            $table->integer('region_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->string('address');

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
        Schema::dropIfExists('citizens');
    }
}
