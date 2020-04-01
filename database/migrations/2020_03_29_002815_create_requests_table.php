<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->longText('message')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('file')->nullable();
            $table->integer('closed_by')->unsigned()->default(0);
            $table->integer('client_id')->unsigned();
            $table->tinyInteger('is_read')->default(0);

            $table->timestamps();

            // $table->foreign('closed_by')
            //     ->references('id')
            //     ->on('users')
            //     ->onDelete('cascade')
            //     ->onUpdate('no action');
            
            // $table->foreign('client_id')
            //     ->references('id')
            //     ->on('users')
            //     ->onDelete('cascade')
            //     ->onUpdate('no action');
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
