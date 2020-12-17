# Electro

> EnerimCIS Product integration for WordPress

## Usage

Add this package `"frc/ve-electro": "*"` as a dependency to `composer.json`. 

Required environment variables:

* `ENERIM_API_BASE_URL` EnermiCIS API base URI
* `ENERIM_API_KEY` EnermiCIS API key (GUID) 
* `ENERIM_API_PARTY_ID` EnermiCIS API default partyId 

## Development

Add this package as local composer dependency to a WordPress project. Then activate the `Electro` plugin.

### UI Development

`src/helpers.php` exposes global functions to get product and product group related data in array.

## Documentation 

See `docs` directory for additional documentation.
