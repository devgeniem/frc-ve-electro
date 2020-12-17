# Products

There are multiple different product types. In code the types are referenced
as `Variants`. All different types are in `src/Product/Variants`.

Correct variant is generated in factory class in `src/Product/Factory.php`. The
class will determine which variant class should be used based on EnerimCIS 
API payload data.

## Variants

### Standard

The most basic product type without special property. Since it's pricing model
is simple, the price estimation can be calculated.

*Finnish term: Määräaikainen.*

### Temporary

Temporary products can have multiple price periods. 

Depending the UI requirements here are the use cases for different price
periods:

`NEXT` price period should be displayed as secondary prices 1 month before it 
it's `valid from` date.`UPCOMING` price period should be displayed as primary 
prices 14 days before it's `valid from` date.
`CURRENT (default)` price period should be displayed as primary prices when the 
`valid from` date is accurate.
`PAST` price period should be displayed as secondary prices when the `valid to` 
date is 2 months from the current date.

Price period are forced to have starting date as 1.1. or 1.7 and are valid 
6 months.

*Finnish terms: Toistaiseksi voimassa oleva, puolivuotistuote.*

### Package

Package product has monthly pricing model and therefore the monthly product 
component is set as a primary component for UI.

*Finnish terms: Pakettisähkö.*

### Spot

Spot product has "marginal" as a primary product component. It also has a
"energy" component but it is filtered out due it's price is always zero. 

The "marginal" component price depends on changing spot price but the info 
doesn't come from the EnerimCIS API. Therefore the info is attached to "marginal" 
component as a meta "+ changing spot price".

*Finnish terms: Spot, aktiivinen.*
