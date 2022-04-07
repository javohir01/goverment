<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('f_name', 30);
            $table->string('l_name', 30);
            $table->string('m_name', 30);

            $table->string('passport', 9)->unique();
            $table->string('pin',14);
            $table->date('birth_date')->nullable();

            $table->integer('region_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('social_id');

            $table->integer('status');
            $table->bigInteger('phone_number');
            $table->text('code');
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
        Schema::dropIfExists('applications');
    }
}
