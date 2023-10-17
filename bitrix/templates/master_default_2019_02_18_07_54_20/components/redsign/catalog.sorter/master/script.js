(function (window){
	'use strict';

	if (window.RSCatalogSorter)
		return;

	window.RSCatalogSorter = function (arParams)
	{

		this.items = [];
		this.errorCode = 0;
          
    this.node = {};
    this.target = null;
    this.sorter = null;
    this.pagination = null;
    this.lazyload = null;

    if (typeof arParams === 'object')
		{
      this.visual = arParams.VISUAL;
    }

		if (this.errorCode === 0)
		{
			BX.ready(BX.delegate(this.init, this));
		}
	};
  
	window.RSCatalogSorter.prototype = {
		init: function()
		{
			var i = 0;

			this.obSorter = BX(this.visual.ID);
			if (!this.obSorter) {
				this.errorCode = -1;
			}
      
      this.items = BX.findChildren(this.obSorter, {tagName: 'a'}, true);
      if (this.items.length > 0) {
        BX.bindDelegate(this.obSorter, 'click', {tagName : 'a'}, BX.proxy(this.catalogRefresh, this));
      }
      
      this.ajaxId = this.visual.TARGET_ID;

      if (this.ajaxId) {
        this.node.target = BX(this.ajaxId);
      }
      
      if (this.node.target) {
        this.node.container = this.node.target.querySelector('[data-entity^="container-"]');
        this.node.pagination = this.node.target.querySelectorAll('[data-entity="pagination"]');
        this.node.lazyload = this.node.target.querySelector('[data-entity="lazyload"]');
      }
    },

		catalogRefresh: function(event)
		{
      var target = BX.proxy_context;

      if (!!target) {
        var data = {},
            url = target.getAttribute('href')/*.split('+').join('%2B')/*.replace(' ', '+')*/; // url fix;
        
        data['action'] = 'catalogRefresh';
        url += (
          url.indexOf('?') < 0
            ? '?'
            : url.slice(-1) != '&'
              ? '&'
              : ''
        ) + 'action=catalogRefresh';
        
        if (this.ajaxId != undefined) {
          url += '&ajax_id=' + this.ajaxId;
        }
        
        if (!this.formPosting)
        {
          this.formPosting = true;
          this.showWait();
          this.sendRequest(url, data);
        }
        
        event.preventDefault();
      }
		},

		sendRequest: function(url, data)
		{
			var defaultData = {
				// siteId: this.siteId,
				// template: this.template,
				// parameters: this.parameters
			};

			if (this.ajaxId)
			{
				defaultData.AJAX_ID = this.ajaxId;
			}

			BX.ajax({
				url: url + (url.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: BX.merge(defaultData, data),
				onsuccess: BX.delegate(function(result){
					if (!result || !result.JS)
						return;

					BX.ajax.processScripts(
						BX.processHTML(result.JS).SCRIPT,
						false,
						BX.delegate(function(){this.showAction(result, data);}, this)
					);
				}, this)
			});
		},
    
		showAction: function(result, data)
		{
			if (!data)
				return;

			switch (data.action)
			{
				case 'catalogRefresh':
					this.processCatalogRefreshAction(result);
					break;
			}
		},

		processCatalogRefreshAction: function(result)
		{
			this.formPosting = false;

			if (result)
			{
				this.refresh(result.sorter);
				this.processSection(result.section);
				// this.processItems(result.items);
				// this.processPagination(result.pagination);
        // this.processLazyload(result.lazyload);
        this.closeWait();
			}
		},

		processSection: function(sectionHtml)
		{
			if (!sectionHtml)
				return;

			var processed = BX.processHTML(sectionHtml, false);

			if (this.node.target)
			{
        this.node.target.innerHTML = processed.HTML
      }

			BX.ajax.processScripts(processed.SCRIPT);
		},

		processItems: function(itemsHtml)
		{
			if (!itemsHtml)
				return;

			var processed = BX.processHTML(itemsHtml, false);

			if (this.node.container)
			{
        this.node.container.innerHTML = processed.HTML
      }

			BX.ajax.processScripts(processed.SCRIPT);
		},

		processPagination: function(paginationHtml)
		{
			if (!paginationHtml)
				return;

			for (var k in this.node.pagination)
			{
				if (this.node.pagination.hasOwnProperty(k) && BX.type.isDomNode(this.node.pagination[k]))
				{
					this.node.pagination[k].innerHTML = paginationHtml;
				}
			}
		},

		// processLazyload: function(lazyloadHtml)
		// {
			// if (!lazyloadHtml) {
        // BX.hide(this.node.lazyload);
      // } else {
        // BX.show(this.node.lazyload);
      // }
		// },

		refresh: function(sorterHtml)
		{
			if (!sorterHtml)
				return;

			var processed = BX.processHTML(sorterHtml, false);

			if (this.obSorter)
			{
        this.obSorter.innerHTML = processed.HTML
      }

			BX.ajax.processScripts(processed.SCRIPT);
		},

		showWait: function()
		{
      BX.addClass(this.node.target, 'overlay is-loading');
		},

		closeWait: function()
		{
      BX.removeClass(this.node.target, 'overlay is-loading');
		},
  }

})(window);
