# Electro

> EnerimCIS Product integration for WordPress

## Usage

Add this package `"frc/ve-electro": "*"` as dependency to `composer.json`. 

Required environment variables:

* `ENERIM_KEY` Private key for SSL certificate
* `ENERIM_CERT` SSL certificate 
* `ENERIM_BASE_URL` EnermiCIS API base URI

Optional environment variables:

* `SOPA_BASE_URL` Base URI for contract site 
* `QUOTAGUARDSTATIC_URL` Proxy URL for static IP

## Development

Add this package as local composer dependency to a WordPress project. Then activate the `Electro` plugin.

### UI Development

`src/helpers.php` exposes global functions to get product and product group related data in array.
