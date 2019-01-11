<?php

namespace App\Console\Commands;

use App\Modules\Catalog\Models\Catalog;
use App\Modules\Catalog\Models\Folder;
use App\Modules\Catalog\Models\Param;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Tester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tester';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //$fileUrl = "http://export.admitad.com/ru/webmaster/websites/228072/products/export_adv_products/?feed_id=1041&code=86a3c10bb0&user=lilkate90&template=7402";

        $destination = storage_path('test.csv');

        //file_put_contents($destination, fopen($fileUrl, 'r'));

        $handle = fopen($destination, 'r');
        $count = 0;

        $catalog = Catalog::find(1);

        if ($handle) {
            $header = fgetcsv($handle, 0, ';');
            //var_dump($handle);
            while (($data = fgetcsv($handle, 0, ';')) !== false) {
                $row = array_map( array(self::class,'convert'), $data );
                $info = array_combine($header, $row);

                info($info);
                return;

                $product = Product::where('provider_id', '=', $info['id'])->first();
                if (!$product) {
                    $product = new Product;
                    $product->provider_id = $info['id'];
                }
                $product->catalog_id = $catalog->id;
                $product->source_id = $catalog->id;
                $product->name = $info['name'];
                $product->description = $info['description'];
                $product->price = $info['price'];
                $product->link = $info['url'];
                $product->url = $info['url'];
                $product->available = $info['available'] == "true";
                $product->delivery = $info['delivery'] == "true";
                $product->sales_notes = $info['sales_notes'];
                $product->save();

                $this->setFolders($product, $info['categoryId']);
                $this->setPictures($product, $info['picture']);
                $this->setParams($product, $info['param']);
                $this->setVendor($product, $info['vendor']);

                $count++;
                if ($count > 10) break;
                unset($data);
            }
        }
    }
    public static function convert( $str )
    {
        return iconv( "Windows-1251", "UTF-8", $str );
    }

    private function setFolders($product, $cats)
    {
        $categories = explode("/", $cats);
        $parent = null;

        foreach ($categories as $c) {
            $folder = Folder::where('catalog_id', '=', $product->catalog_id)->where('name', '=', $c);
            if ($parent) {
                $folder->where('parent_id', '=', $parent->id);
            }
            $folder = $folder->first();
            if (!$folder) {
                $folder = new Folder;
                $folder->catalog_id = $product->catalog_id;
            }
            $order = Folder::where('catalog_id', '=', $product->catalog_id);
            if ($parent) {
                $order->where('parent_id', '=', $parent->id);
            }
            $folder->order = $order->count();
            $folder->source_id = $product->source_id;
            $folder->name = $c;
            $folder->url = $c;
            if ($parent) {
                $folder->parent_id = $parent->id;
            } else {
                $folder->parent_id = 0;
            }
            $folder->save();
            $parent = $folder;
        }
        $product->folders()->save($parent);
    }

    private function setPictures($product, $img)
    {

        //$url = $img;
        //$contents = file_get_contents($url);
        //$name = substr($url, strrpos($url, '/') + 1);
        //Storage::put($name, $contents);

    }

    private function setParams($product, $pars)
    {

        $ps = explode("|", $pars);

        foreach ($ps as $p) {
            $s = explode(":", $p);
            $s = array_map('trim', $s);


            $param = Param::where('name', $s[0])->first();

            if (!$param) {
                $param = new Param;
                $param->name = $s[0];
                $param->type = 'text';
            }


        }

    }

    private function setVendor($product, $v)
    {

        $vendor = Vendor::where('catalog_id', '=', $product->catalog_id)->where('name', '=', $v);

        $vendor = $vendor->first();

        if (!$vendor) {
            $vendor = new Vendor();
            $vendor->catalog_id = $product->catalog_id;
        }
        $order = Vendor::where('catalog_id', '=', $product->catalog_id);

        $vendor->order = $order->count();
        $vendor->source_id = $product->source_id;
        $vendor->name = $v;
        $vendor->url = $v;

        $vendor->save();

    }
}
