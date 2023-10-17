window.JCCatalogStoreSKU = function(params)
{
	var i;

	if(!params)
		return;

	this.config = {
		'id' : params.ID,
		'showEmptyStore'	: params.SHOW_EMPTY_STORE,
		'useMinAmount'		: params.USE_MIN_AMOUNT,
		'minAmount'			: params.MIN_AMOUNT
	};

	this.messages = params.MESSAGES;
	this.icons = params.ICONS;
	this.sku = params.SKU;
	this.stores = params.STORES;
	this.obStores = {};
	for (i in this.stores)
		this.obStores[this.stores[i]] = BX(this.config.id+"_"+this.stores[i]);

	BX.addCustomEvent(window, "onCatalogStoreProductChange", BX.proxy(this.offerOnChange, this));
};

window.JCCatalogStoreSKU.prototype.offerOnChange = function(id)
{
	if (!this.sku[id])
		return;

	var curSku = this.sku[id],
		k,
		icon,
		message,
		parent;

	for (k in this.obStores)
	{
		icon = this.getStringCount(0, this.icons);
		message = (!!this.config.useMinAmount) ? this.getStringCount(0, this.messages) : '0';
		BX.adjust(BX.findChild(this.obStores[k], {className: 'stock__icon'}), {html: icon});
		BX.adjust(BX.findChild(this.obStores[k], {className: 'stock__val'}), {html: message});
		if (!!curSku[k])
		{
			icon = this.getStringCount(curSku[k], this.icons) + ' ';
			message = (!!this.config.useMinAmount) ? this.getStringCount(curSku[k], this.messages) : curSku[k];

      BX.adjust(BX.findChild(this.obStores[k], {className: 'stock__icon'}), {html: icon });
      BX.adjust(BX.findChild(this.obStores[k], {className: 'stock__val'}), {html: message});
		}
		parent = BX.findParent(this.obStores[k], {tagName: 'li'});
		if (!!this.config.showEmptyStore || curSku[k] > 0)
			BX.show(parent);
		else
			BX.hide(parent);

    if (curSku[k] > 0) {
      BX.removeClass(parent, 'is-empty');
    } else {
      BX.addClass(parent, 'is-empty');
    }
	}
};

window.JCCatalogStoreSKU.prototype.getStringCount = function(num, arMessage)
{
	if (num == 0)
		return arMessage['ABSENT'];
	else if (num >= this.config.minAmount)
		return arMessage['LOT_OF_GOOD'];
	else
		return arMessage['NOT_MUCH_GOOD'];
};