# Admin

Plugin will add three custom menu item to WordPress' admin menu.

## Enerim Products (Custom Post Type)

The `Enerim Products` contains all products imported from EnerimCIS API. The
title in WordPress is product name and the title is used as ID.

Single product has a view with original API payload and custom presenter data
generated in `src/Presenters/ProductPresenter.php`. Use the presenter data to
check that generated data for UI is correct. 

### Upload JSON

It is possible to upload a JSON file containing data formatted as EnerimCIS API
payload to import product(s). 

Originally this feature was developed for situation where the EnerimCIS API
would be down. But also to test products with modified attributes - like campaign
products.

### SOPA link generator

Helper feature for content managers to generate direct links to SOPA.

## Product Groups (Custom Post Type)

The `Product Groups` are displayed for example in contract picker. One group can
have multiple `Enerim Products` but share same meta data like description and icon.

### Fields

**Products**

- `Product` single Enerim Product
- `Consumption Min/Max` - the product visibility can be toggled if user set 
consumption is over or under these values
- `Custom key/value` - used to replace default `selectedProductCode` parameter 
in SOPA link.For example in additional products it used as `excange=3,9`.
By default key is `productCode` and value is the selected Enerim Proudct's name.

**Description** 

Displayed in product card.

**Additional description** 

Displayed in product card's modal.

**Additional products** 

Attached additional products added as `Additional Products` posts.

## Additional Products (Custom Post Type)

The `Additional Products` are similar compared to `Product Groups` and have same
fields except the `Additional products` field. 

Additional Products are a separate custom post type to make admin more clear between 
`Product Groups` and `Additional Products`.

In the future EnerimCIS API should return additional products in the response 
as well and `Additional Products` custom post type logic can be removed from the
source. 
