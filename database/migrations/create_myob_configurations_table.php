<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyobConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('myob_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->text('access_token');
            $table->text('refresh_token');
            $table->string('scope');
            $table->DateTime('expires_at');
            $table->uuid('company_file_guid')->nullable();
            $table->string('company_file_token')->nullable();
            $table->string('company_file_uri')->nullable();
            $table->string('company_file_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {

    }
}