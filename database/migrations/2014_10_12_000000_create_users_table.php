<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('number')->unique()->nullable();
            $table->integer('phone_number')->unique()->nullable();
            $table->string('type')->nullable();
            $table->date('birthDate')->nullable();
            $table->string('photo')->nullable();
            $table->string('course')->nullable();
            $table->integer('curricular_year')->nullable();
            $table->string('school')->nullable();
            $table->boolean('enee')->nullable();
            $table->integer('cc')->nullable();
            $table->integer('nif')->nullable();
            $table->integer('niss')->nullable();
            $table->integer('sns')->nullable();
            $table->integer('nee_severity')->nullable();
            $table->date('eneeExpirationDate')->nullable();
            $table->date('loginExpirationDate')->nullable();
            $table->string('password');
            $table->boolean('firstLogin')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->integer('year');
            $table->integer('semester');
            $table->string('name');
            $table->string('typeOfEvaluation');
            $table->integer('grade');
        });

        Schema::create('meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('service');
            $table->integer('studentId');
            $table->string('email');
            $table->string('comment');
            $table->string('info')->nullable();
            $table->date('date')->nullable();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('name');
            $table->date('expirationDate');
        });

        Schema::create('tutor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('studentEmail')->unique();
            $table->string('tutorEmail')->unique();
        });

        Schema::create('case manager', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('studentEmail')->unique();
            $table->string('caseManagerEmail')->unique();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('studentEmail');
            $table->date('date');
            $table->string('service')->nullable();
            $table->string('decision');
            $table->text('information');
            $table->date('nextContact');
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
