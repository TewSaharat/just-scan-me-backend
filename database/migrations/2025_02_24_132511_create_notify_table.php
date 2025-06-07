<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('notify', function (Blueprint $table) {
            $table->id('cat_id');
            $table->string('lamp_type', 255)->nullable();
            $table->string('dir', 255)->nullable();
            $table->string('dir_num', 255)->nullable();
            $table->string('routes', 255)->nullable();
            $table->string('control', 255)->nullable();
            $table->string('km', 255)->nullable();
            $table->decimal('lat', 12, 9)->nullable();
            $table->decimal('longitude', 12, 9)->nullable();
            $table->float('fovy')->nullable();
            $table->float('ranges')->nullable();
            $table->string('name_id', 255)->nullable();
            $table->integer('status')->nullable();
            $table->string('lampType_edit', 255)->nullable();
            $table->string('controller_edit', 255)->nullable();
            $table->string('constructionDate', 255)->nullable();
            $table->string('contractNumber', 255)->nullable();
            $table->string('notes', 255)->nullable();
            $table->string('repairMethod', 255)->nullable();
            $table->string('complaintChannel', 255)->nullable();
            $table->string('complaintCode', 255)->nullable();
            $table->string('complaintTopic', 255)->nullable();
            $table->string('complaintReason', 255)->nullable();
            $table->string('repairParts', 255)->nullable();
            $table->string('controlType', 255)->nullable();
            $table->string('lastRepairDate', 255)->nullable();
            $table->string('report_time', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('notify');
    }
};
