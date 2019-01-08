<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTables extends Migration
{
    public function up()
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('domain')->nullable()->index();
            $table->unsignedInteger('design_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('design_id')->references('id')->on('designs');
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('source');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('variables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('name');
            $table->string('type');
            $table->string('method');
            $table->boolean('shared')->default(false);
            $table->timestamps();
        });

        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('design_id')->nullable()->index();
            $table->unsignedInteger('module_id')->nullable()->index();
            $table->boolean('is_page')->default(false);
            $table->text('body');
            $table->timestamps();

            $table->foreign('design_id')->references('id')->on('designs');
            $table->foreign('module_id')->references('id')->on('modules');
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('site_id')->index();
            $table->string('name');
            $table->boolean('is_main')->default(false);
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('menu_id')->index();
            $table->string('name');
            $table->unsignedInteger('template_id')->index();
            $table->unsignedInteger('design_id')->nullable()->index();
            $table->string('url');
            $table->boolean('is_main')->default(false);
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('template_id')->references('id')->on('templates');
        });

        Schema::create('design_variable', function (Blueprint $table) {
            $table->unsignedInteger('design_id');
            $table->unsignedInteger('variable_id')->index();

            $table->primary(['design_id', 'variable_id']);
            $table->foreign('variable_id')->references('id')->on('variables');
        });

        Schema::create('template_variable', function (Blueprint $table) {
            $table->unsignedInteger('template_id');
            $table->unsignedInteger('variable_id')->index();

            $table->primary(['template_id', 'variable_id']);
            $table->foreign('variable_id')->references('id')->on('variables');
        });

        Schema::create('item_variable', function (Blueprint $table) {
            $table->unsignedInteger('variable_id')->index();
            $table->unsignedInteger('page_id')->nullable()->index();
            $table->unsignedInteger('item_type');
            $table->unsignedInteger('item_id');

            $table->foreign('page_id')->references('id')->on('pages');
            $table->foreign('variable_id')->references('id')->on('variables');
        });

        Schema::create('module_site', function (Blueprint $table) {
            $table->unsignedInteger('site_id');
            $table->unsignedInteger('module_id')->index();

            $table->primary(['site_id', 'module_id']);
            $table->foreign('module_id')->references('id')->on('modules');
        });
    }

    public function down()
    {
        Schema::dropIfExists('module_site');
        Schema::dropIfExists('item_variable');
        Schema::dropIfExists('template_variable');
        Schema::dropIfExists('design_variable');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('variables');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('sites');
        Schema::dropIfExists('designs');
    }
}



