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
            $table->integer('phoneNumber')->unique()->nullable();
            $table->string('residence')->nullable();
            $table->string('zipCode')->nullable();
            $table->string('area')->nullable();
            $table->string('type')->nullable();
            $table->date('birthDate')->nullable();
            $table->string('photo')->nullable();
            $table->string('course')->nullable();
            $table->integer('curricularYear')->nullable();
            $table->integer('enruledYear')->nullable();
            $table->string('school')->nullable();
            $table->string('identificationDocument')->nullable();
            $table->integer('identificationNumber')->nullable();
            $table->bigInteger('nif')->nullable();
            $table->bigInteger('niss')->nullable();
            $table->bigInteger('sns')->nullable();
            $table->boolean('enee')->nullable();
            $table->text('educationalSupport')->nullable();
            $table->date('eneeExpirationDate')->nullable();
            $table->date('loginExpirationDate')->nullable();
            $table->string('password');
            $table->boolean('firstLogin')->nullable();
            $table->string('responsibleName')->nullable();
            $table->integer('responsiblePhone')->nullable();
            $table->string('responsibleEmail')->nullable();
            $table->string('responsibleKin')->nullable();
            $table->string('emergencyName')->nullable();
            $table->integer('emergencyPhone')->nullable();
            $table->string('emergencyEmail')->nullable();
            $table->string('emergencyKin')->nullable();
            $table->text('functionalAnalysis')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('medical_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('fileName');
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
            $table->string('name');
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
            $table->string('reason')->nullable();
            $table->date('expirationDate')->nullable();
            $table->date('aprovedDate')->nullable();
        });

        Schema::create('tutors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('studentEmail')->unique();
            $table->string('tutorEmail')->unique();
        });

        Schema::create('case_managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('studentEmail')->unique();
            $table->string('studentName');
            $table->string('caseManagerEmail')->unique();
            $table->string('caseManagerName');
            $table->string('approved')->nullable();
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

        Schema::create('nees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('studentEmail');
            $table->string('name');
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
        Schema::dropIfExists('case_managers');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('migrations');
        Schema::dropIfExists('services');
        Schema::dropIfExists('tutors');
        Schema::dropIfExists('contacts');
    }
}
