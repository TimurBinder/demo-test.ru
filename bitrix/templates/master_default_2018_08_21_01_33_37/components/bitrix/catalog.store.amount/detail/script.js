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

  this.obTotal = BX(this.config.id+'_total');

	BX.addCustomEvent(window, "onCatalogStoreProductChange", BX.proxy(this.offerOnChange, this));
};

window.JCCatalogStoreSKU.prototype.offerOnChange = function(id)
{
	var curSku = this.sku[id],
		k,
		icon,
		message,
		total = 0,
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
      
      total += curSku[k];
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
  
  if (this.obTotal) {
    icon = this.getStringCount(total, this.icons);
    message = (!!this.config.useMinAmount) ? this.getStringCount(total, this.messages) : total;
    BX.adjust(BX.findChild(this.obTotal, {className: 'stock__val'}, true), {html: message});
    
    BX.adjust(BX.findChild(this.obTotal, {className: 'stock__icon'}), {html: icon });
    BX.adjust(BX.findChild(this.obTotal, {className: 'stock__val'}), {html: message});
    
    if (total > 0) {
      BX.removeClass(this.obTotal.parentNode, 'is-empty');
    } else {
      BX.addClass(this.obTotal.parentNode, 'is-empty');
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