# Acculance v4.0.0

## Running Demo Seeder

```shell
php artisan migrate:fresh --seed --seeder=DemoDatabaseSeeder
```

## For Production Setup

1. Remove installed and verified files from storage folder
2. Set IS_DEMO_MODE=false in the .env file


## For installer

1) Add "notVerified" middleware in package web routes
2) Add welcome path in package web routes
3) Add withSuccess in EnvironmentController "saveClassic" method
4) Add JWT secret in Environment Helper


### Please follow the below steps to run the application for import functionality


1. Create `demo-csv-file` folder in the `public` directory of the project
2. Create these empty files in the `demo-csv-file` folder
    * `brand.csv`
    * `sub-categories.csv`
    * `taxes.csv`
    * `units.csv`
3. Provide these two files in the `demo-csv-file` folder for examples
    * `products.csv` (name, model, barcode_symbology, sub_cat_id, brand_id, unit_id, tax_id, tax_type, regular_price, discount, note, alert_qty, status )
    * `demo.csv` (name, phone, email, company_name, address)
