# WooCommerce Square Integration

### Design Decisions

* WooCommerce products need to have a SKU (at least one variation needs a SKU if a variable product) in order to be synced.

* Square doesn't support hierarchy in Categories, so only top-level WooCommerce Product Categories are synced. When products are synced, the top-level parent of whatever category is assigned will be sent to Square.

### Known API Limitations

There is no search. On any of the endpoints. If you want to find a product by SKU, you need to retrieve all of the products, and comb through them.

**The API supports bulk operations. We should use them wherever possible.**

### Initial Sync

If the desired sync behavior is bi-directional, who should win when both systems have an item with a matching SKU?

## WooCommerce to Square

### Create new product

* Create new product in Square API
	* `POST /v1/{merchant_id}/items/`
* If managing stock, update inventory
	* `POST /v1/{merchant_id}/inventory/{variation_id}` for each variation
* If product has a featured image, upload
	* `POST /v1/{merchant_id}/items/{item_id}/image`

### Update existing product

* Update Item
	* `PUT /v1/{merchant_id}/items/{item_id}`
* Sync variations (even simple products will need one variation in Square)
	* Variation has a Square Variation ID:
		* `PUT /v1/{merchant_id}/items/{item_id}/variations/{variation_id}`
	* Variation doesn't have a Square Variation ID:
		* `POST /v1/{merchant_id}/items/{item_id}/variations`
* If managing stock, update inventory
	* `POST /v1/{merchant_id}/inventory/{variation_id}` for each variation
* Update product image, if needed
	* `POST /v1/{merchant_id}/items/{item_id}/image`

### 'save_post' behavior

_**Assuming WC and Square inventories are synced**_

* Product exists in Square (has Square Item ID)
	* See "Create new product" above
* Product doesn't have Square Item ID
	* See "Update existing product" above

This assumes that when the inventories are in sync, no SKU checking is actually needed because both WooCommerce and Square's web dashboards prevent duplicate SKUs from being created.

The Square Connect API v1 currently allows duplicate SKUs, but we believe this to be a bug. An inquiry has been opened with the Square development team.

_**Assuming WC and Square inventories are NOT synced**_

* Retrieve all items from Square API
	* `GET /v1/{merchant_id}/items/`
	* This could require fetching multiple pages of results
* Look for product having a variation with matching SKU
	* If SKU match found:
		* Update Item
			* `PUT /v1/{merchant_id}/items/{item_id}`
		* Sync variations (even simple products have one variation in Square)
			* WC Variation matches a Square variation SKU
				* `PUT /v1/{merchant_id}/items/{item_id}/variations/{variation_id}`
			* WC Variation doesn't match a Square variation SKU
				* `POST /v1/{merchant_id}/items/{item_id}/variations`
		* If managing stock, update inventory
			* `POST /v1/{merchant_id}/inventory/{variation_id}` for each variation
		* Update product image, if needed
			* `POST /v1/{merchant_id}/items/{item_id}/image`
	* If no SKU match:
		* See "Create a product" above
