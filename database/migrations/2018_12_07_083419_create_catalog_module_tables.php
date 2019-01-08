<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogModuleTables extends Migration
{
    public function up()
    {
        Schema::create('m_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('site_id')->nullable()->index();
            $table->string('name');
            $table->string('filename');
            $table->unsignedSmallInteger('width')->default(0);
            $table->unsignedSmallInteger('height')->default(0);
            $table->char('ext', 7);
            $table->unsignedInteger('size')->default(0);
            $table->string('download_url')->nullable();
            $table->string('md5');
            $table->timestamps();
        });
        Schema::create('m_catalogs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('site_id')->nullable()->index();
            $table->string('name');
            $table->unsignedSmallInteger('setting_products_per_page');
            $table->string('setting_sort')->default('created_at');
            $table->string('setting_available_mode')->default('hide');
            $table->timestamps();
        });
        Schema::create('m_catalog_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('catalog_id')->nullable()->index();
            $table->string('name');
            $table->boolean('active');
            $table->boolean('update');
            $table->string('update_mode');
            $table->dateTime('update_period');
            $table->string('type');
            $table->text('settings');
            $table->timestamps();
        });
        Schema::create('m_catalog_vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('catalog_id');
            $table->unsignedInteger('source_id');
            $table->string('name');
            $table->string('url');
            $table->integer('order');
            $table->timestamps();
        });
        Schema::create('m_catalog_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0);
            $table->unsignedInteger('catalog_id');
            $table->unsignedInteger('source_id');
            $table->string('name');
            $table->string('url');
            $table->integer('order');
            $table->timestamps();
        });
        Schema::create('m_catalog_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('provider_id');
            $table->unsignedInteger('catalog_id');
            $table->unsignedInteger('source_id');
            $table->string('name');
            $table->boolean('available');
            $table->boolean('delivery');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->text('sales_notes');
            $table->text('description');
            $table->double('price', 12, 2);
            $table->string('url');
            $table->string('link');
            $table->timestamps();
        });
        Schema::create('m_catalog_product_params', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('catalog_id');
            $table->unsignedInteger('source_id');
            $table->string('name');
            $table->string('type');
            $table->timestamps();
        });

        /**
         * links
        */
        Schema::create('m_catalog_folder_product', function (Blueprint $table) {
            $table->unsignedInteger('folder_id');
            $table->unsignedInteger('product_id');
            $table->timestamps();
        });

        Schema::create('m_catalog_folder_param', function (Blueprint $table) {
            $table->unsignedInteger('folder_id');
            $table->unsignedInteger('param_id');
            $table->timestamps();
        });
        Schema::create('m_catalog_vendor_param', function (Blueprint $table) {
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('param_id');
            $table->timestamps();
        });

        Schema::create('m_catalog_image_product', function (Blueprint $table) {
            $table->unsignedInteger('image_id');
            $table->unsignedInteger('product_id');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('m_catalog_param_product', function (Blueprint $table) {
            $table->unsignedInteger('param_id');
            $table->unsignedInteger('product_id');
            $table->string('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_images');
        Schema::dropIfExists('m_catalogs');
        Schema::dropIfExists('m_catalog_sources');
        Schema::dropIfExists('m_catalog_vendors');
        Schema::dropIfExists('m_catalog_folders');
        Schema::dropIfExists('m_catalog_products');
        Schema::dropIfExists('m_catalog_product_params');

        Schema::dropIfExists('m_catalog_folder_product');
        Schema::dropIfExists('m_catalog_folder_param');
        Schema::dropIfExists('m_catalog_vendor_param');
        Schema::dropIfExists('m_catalog_image_product');
        Schema::dropIfExists('m_catalog_param_product');
    }
}
