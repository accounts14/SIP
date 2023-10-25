<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDiscount;
use App\Models\ProductMaterial;
use App\Models\ProductUnit;
use App\Models\ProductVariant;
use App\Models\ProductVariantSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product Category
        ProductCategory::firstOrCreate([
            'id' => 1,
            'brand_id' => 1,
            'product_type_id' => 1,
            'name' => 'Promo',
        ]);
        ProductCategory::firstOrCreate([
            'id' => 2,
            'brand_id' => 1,
            'product_type_id' => 1,
            'name' => 'Kaos',
        ]);
        ProductCategory::firstOrCreate([
            'id' => 3,
            'brand_id' => 1,
            'product_type_id' => 1,
            'name' => 'Sweater',
        ]);
        ProductCategory::firstOrCreate([
            'id' => 4,
            'brand_id' => 2,
            'product_type_id' => 2,
            'name' => 'Promo',
        ]);
        ProductCategory::firstOrCreate([
            'id' => 5,
            'brand_id' => 2,
            'product_type_id' => 2,
            'name' => 'Akrilik',
        ]);

        // Product Unit
        ProductUnit::firstOrCreate([
            'id' => 1,
            'brand_id' => 1,
            'product_type_id' => 1,
            'name' => 'pcs',
        ]);
        ProductUnit::firstOrCreate([
            'id' => 2,
            'brand_id' => 2,
            'product_type_id' => 2,
            'name' => 'day',
        ]);

        // Product Material
        ProductMaterial::firstOrCreate([
            'id' => 1,
            'brand_id' => 1,
            'product_type_id' => 1,
            'name' => 'Cotton Combed 30s',
        ]);
        ProductMaterial::firstOrCreate([
            'id' => 2,
            'brand_id' => 1,
            'product_type_id' => 1,
            'name' => 'Knit',
        ]);
        ProductMaterial::firstOrCreate([
            'id' => 3,
            'brand_id' => 2,
            'product_type_id' => 2,
            'name' => 'Akrilik 3mm',
        ]);
        ProductMaterial::firstOrCreate([
            'id' => 4,
            'brand_id' => 2,
            'product_type_id' => 2,
            'name' => 'Akrilik 2mm',
        ]);

        // Product Discount
        ProductDiscount::firstOrCreate([
            'id' => 1,
            'brand_id' => 1,
            'product_type_id' => 1,
            'name' => 'Grand Opening',
            'value' => 20,
            'description' => 'Discount ini berlaku hingga 1 minggu setelah grand opening',
        ]);
        ProductDiscount::firstOrCreate([
            'id' => 2,
            'brand_id' => 2,
            'product_type_id' => 2,
            'name' => 'Grand Opening',
            'value' => 20,
            'description' => 'Discount berlaku dalam 3 hari',
        ]);

        // 1
        Product::firstOrCreate([
            'id' => 1,
            'brand_id' => 1,
            'code' => 'K0801',
            'name' => 'Kaos Cotton Combed 30s',
            'description' => 'Bahan Lembut bla bla bla',
            'product_type_id' => 1,
            'product_published' => 0,
        ]);
        ProductVariant::firstOrCreate([
            'id' => 1,
            'brand_id' => 1,
            'product_id' => 1,
            'product_material_id' => 1,
            'product_unit_id' => 1,
            'variant_name' => 'Blue',
            'variant_image' => null,
            'product_discount_id' => 1,
            'variant_description' => 'Warna biru tua',
        ]);
        ProductVariant::firstOrCreate([
            'id' => 2,
            'brand_id' => 1,
            'product_id' => 1,
            'product_material_id' => 1,
            'product_unit_id' => 1,
            'variant_name' => 'Green',
            'variant_image' => null,
            'product_discount_id' => 1,
            'variant_description' => 'Warna hijau tua',
        ]);
        ProductVariantSize::firstOrCreate([
            'brand_id' => 1,
            'product_variant_id' => 1,
            'name' => 'XL',
            'price' => 90000,
            'stock' => 2,
            'cost' => 65000,
        ]);
        ProductVariantSize::firstOrCreate([
            'brand_id' => 1,
            'product_variant_id' => 1,
            'name' => 'XXL',
            'price' => 100000,
            'stock' => 2,
            'cost' => 75000,
        ]);
        ProductVariantSize::firstOrCreate([
            'brand_id' => 1,
            'product_variant_id' => 2,
            'name' => 'S',
            'price' => 75000,
            'stock' => 2,
            'cost' => 50000,
        ]);

        // 2
        Product::firstOrCreate([
            'id' => 2,
            'brand_id' => 1,
            'code' => 'S0801',
            'name' => 'Sweater',
            'description' => 'Bahan Lembut bla bla bla',
            'product_type_id' => 1,
            'product_published' => 0,
        ]);
        ProductVariant::firstOrCreate([
            'id' => 3,
            'brand_id' => 1,
            'product_id' => 2,
            'product_material_id' => 1,
            'product_unit_id' => 1,
            'variant_name' => 'Red',
            'variant_image' => null,
            'product_discount_id' => 1,
            'variant_description' => 'Warna merah tua',
        ]);
        ProductVariant::firstOrCreate([
            'id' => 4,
            'brand_id' => 1,
            'product_id' => 2,
            'product_material_id' => 1,
            'product_unit_id' => 1,
            'variant_name' => 'Yellow',
            'variant_image' => null,
            'product_discount_id' => 1,
            'variant_description' => 'Warna kuning tua',
        ]);
        ProductVariantSize::firstOrCreate([
            'brand_id' => 1,
            'product_variant_id' => 3,
            'name' => 'XL',
            'price' => 90000,
            'stock' => 2,
            'cost' => 65000,
        ]);
        ProductVariantSize::firstOrCreate([
            'brand_id' => 1,
            'product_variant_id' => 3,
            'name' => 'XXL',
            'price' => 100000,
            'stock' => 2,
            'cost' => 75000,
        ]);
        ProductVariantSize::firstOrCreate([
            'brand_id' => 1,
            'product_variant_id' => 4,
            'name' => 'S',
            'price' => 75000,
            'stock' => 2,
            'cost' => 50000,
        ]);

        // 3
        Product::firstOrCreate([
            'id' => 3,
            'brand_id' => 2,
            'code' => 'A-471',
            'name' => 'Akrilik Hitam Polos 40cm x 70cm',
            'description' => 'Gratis Ongkir khusus Area Salatiga dan sekitarnya',
            'product_type_id' => 2,
            'product_published' => 0,
        ]);
        ProductVariant::firstOrCreate([
            'id' => 5,
            'brand_id' => 2,
            'product_id' => 3,
            'product_material_id' => 2,
            'product_unit_id' => 2,
            'variant_name' => 'Black',
            'variant_image' => null,
            'product_discount_id' => 2,
            'variant_description' => 'Warna hitam pekat',
        ]);
        ProductVariantSize::firstOrCreate([
            'brand_id' => 2,
            'product_variant_id' => 5,
            'name' => '40x60(cm)',
            'price' => 100000,
            'stock' => null,
            'cost' => null,
        ]);

        $productCategoryData = [
            ['product_id' => 1, 'product_category_id' => 1],
            ['product_id' => 1, 'product_category_id' => 2],
            ['product_id' => 2, 'product_category_id' => 1],
            ['product_id' => 2, 'product_category_id' => 3],
            ['product_id' => 3, 'product_category_id' => 4],
            ['product_id' => 3, 'product_category_id' => 5],
        ];
        DB::table('product_product_category')->insert($productCategoryData);
    }
}
