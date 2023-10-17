BX.namespace("BX.Iblock.Catalog");

BX.Iblock.Catalog.CompareClass = (function()
{
	var CompareClass = function(arParams)
	{
    this.app = RS.Application();

    this.errorCode = 0;
    this.obCompare = null;
    this.node = {};
    this.config = {
			name: 'CATALOG_COMPARE_LIST',
			iblockId: null,
    };

    this.items = [];
    this.obItems = [];

    if (typeof arParams === 'object')
		{
      this.visual = arParams.VISUAL;

      this.config.name = arParams.CONFIG.NAME;
      this.config.iblockId = arParams.CONFIG.IBLOCK_ID;
      this.ajaxUrl = arParams.CONFIG.TEMPLATE_FOLDER + '/ajax.php';

      this.items = arParams.ITEMS;
    }

    if (this.app.breakpoints) {
      this.breakpoints = this.app.breakpoints;
    } else {
      this.breakpoints = {
        xxs: 0,
        xs: 480,
        sm: 768,
        md: 992,
        lg: 1200
      };
    }

		if (this.errorCode === 0)
		{
			BX.ready(BX.delegate(this.init, this));
		}
  };

  CompareClass.prototype = {
		init: function()
		{
      var i;

      this.obCompare = BX(this.visual.ID);
      this.obTable = BX(this.visual.TABLE);

      if (!this.obCompare) {
        this.errorCode = -1;
      }

      this.node.columnNames = this.obCompare.querySelector('[data-entity="column-names"]');
      this.node.columnItems = this.obCompare.querySelector('[data-entity="column-items"]');
      this.node.itemsTable = this.obCompare.querySelector('[data-entity="items-table"]');

      this.obItems = [];

      if (this.node.itemsTable) {
        var items = this.node.itemsTable.querySelectorAll('[data-entity="compare-item"]');
        for (i in items)
        {
          if (items.hasOwnProperty(i))
          {
            this.obItems.push(items[i])
          }
        }
      }

      for (i in this.obItems) {
        BX.bind(this.obItems[i], 'mouseover', BX.proxy(this.compareItemDragInit, this));
      }

      this.setRhythm();

      var columnItems = this.node.columnItems;

      $(this.node.columnItems).scrollbar({
        // showArrows: true,
        // scrollx: this.node.scrollWrap,
        // scrollStep: 104
        onUpdate: function(a) {
          var $tableHead = $(columnItems).find('thead'),
              $scroll = $(this.wrapper).find('.scroll-element.scroll-x');

          $scroll.css({
            'top': $tableHead.position().top
                   + $tableHead.outerHeight()
                   + $(columnItems).find('tbody > tr:first-child').outerHeight()/2
                   - $scroll.outerHeight()/2
          });
        }
      });

      BX.data(this.obCompare, "cmpRes", this);

      $(window).on('resize', BX.debounce($.proxy(this.setRhythm, this), 250));

      BX.addCustomEvent(window, "OnCompareSort", BX.proxy(this.compareSort, this));
    },

		MakeAjaxAction: function(url, event)
		{
      this.showWait();

      BX.ajax.post(
        url,
        {
          ajax_action: 'Y',
          ajax_id: this.visual.ID
        },
        BX.proxy(function(result)
        {
          this.obCompare.innerHTML = result;
          this.init();
          this.closeWait();

        }, this)
      );

      if (event) {
        BX.PreventDefault(event);
      }
    },

		compareSort: function()
		{
      var data = {
        action: 'compare-sort',
        ITEMS: [],
      };

      if (this.node.itemsTable) {
        var items = this.node.itemsTable.querySelectorAll('[data-entity="compare-item"]');
        for (i in items)
        {
          if (items.hasOwnProperty(i))
          {
            var index = this.obItems.indexOf(items[i]);
            data.ITEMS.push(this.items[index]);
          }
        }
      }

      this.sendRequest(data);
    },

		compareSortResult: function()
		{
    },

		sendRequest: function(data)
		{
			var defaultData = {
				siteId: this.siteId,
        AJAX: 'Y',
        NAME: this.config.name,
        IBLOCK_ID: this.config.iblockId,
			};

			BX.ajax({
        method: 'POST',
        dataType: 'json',
        url: this.ajaxUrl,
        data: BX.merge(defaultData, data),
        onsuccess: BX.delegate(function(result){
					this.showAction(result, data)
				}, this)
			});
		},

		showAction: function(result, data)
		{
			if (!data)
				return;

			switch (data.action)
			{
				case 'compare-sort':
					this.compareSortResult(result);
					break;
			}
		},

		setRhythm: function()
		{
      var rowsData =  BX.findChildren(this.node.itemsTable, {'tag': 'tr'}, true),
          rowsName =  BX.findChildren(this.node.columnNames, {'tag': 'tr'}, true);

      if (!!rowsName && rowsName.length > 0
        && !!rowsData && rowsData.length)
      {
        var match = -1,
            responsive = {};

        responsive[this.breakpoints.xxs] = {
          items: 1
        };
        responsive[this.breakpoints.sm] = {
          items: 2
        };
        responsive[this.breakpoints.md] = {
          items: 3
        };

        for (var breakpoint in responsive)
        {
          breakpoint = Number(breakpoint);

          if (breakpoint <= this.app.getWidth() && breakpoint > match) {
            match = breakpoint;
          }
        }

        this.node.itemsTable.style.width = (100 * BX.findChildren(rowsData[0], {'tag': 'td'}, true).length / responsive[match].items) + '%';
        for (i = 0; rowsName.length > i; i++)
        {
          rowsName[i].style.height = rowsData[i].style.height = 'auto';

          if(rowsData[i].offsetHeight > rowsName[i].offsetHeight)
          {
            rowsName[i].style.height = rowsData[i].offsetHeight + 'px';
          }
          else
          {
            rowsData[i].style.height = rowsName[i].offsetHeight + 'px';
          }
        }
      }
    },

		showWait: function()
		{
      BX.addClass(this.obCompare, 'overlay is-loading');
    },

		closeWait: function()
		{
      BX.removeClass(this.obCompare, 'overlay is-loading');
    },

		compareItemDragStart: function()
		{
      var div = document.body.appendChild(
        document.createElement("DIV")
      );
      div.style.position = 'absolute';
      div.style.zIndex = 1100;
      div.className = 'bx-drag-object';
      div.innerHTML = this.innerHTML;
      div.style.width = this.clientWidth+'px';
      this.__dragCopyDiv = div;
      // this.className += ' bx-drag-source';
/*
      var arrowDiv = document.body.appendChild(document.createElement("DIV"));
      arrowDiv.style.position = 'absolute';
      arrowDiv.style.zIndex = 1110;
      arrowDiv.className = 'bx-drag-arrow';
      this.__dragArrowDiv = arrowDiv;
*/
      return true;
    },

		compareItemDrag: function(x, y)
		{
      var div = this.__dragCopyDiv,
          dest = this.__currentDest;

      div.style.left = x+'px';
      div.style.top = y+'px';

      if (dest) {
        var pos = BX.pos(dest);
        if (x - pos.left < pos.width / 2) {

          var prev = BX.previousSibling(dest);
          if (prev) {
            BX.addClass(prev, 'is-hover');
          }
          BX.removeClass(dest, 'is-hover');

        } else {
          var prev = BX.previousSibling(dest);
          if (prev) {
            BX.removeClass(prev, 'is-hover');
          }
          BX.addClass(dest, 'is-hover');
        }
      }

      return true;
    },

		compareItemDragStop: function()
		{
      this.className = this.className.replace(/\s*bx-grid-drag-source/ig, "");

      this.__dragCopyDiv.parentNode.removeChild(this.__dragCopyDiv);
      this.__dragCopyDiv = null;
/*
      this.__dragArrowDiv.parentNode.removeChild(this.__dragArrowDiv);
      this.__dragArrowDiv = null;
*/
		return true;
    },

		compareItemDragHover: function(dest, x, y)
		{
      if (this != dest) {
        this.__currentDest = dest;
      } else {
        this.__currentDest = null;
      }
    },

		compareItemDragOut: function(dest, x, y)
		{
      var prev = BX.previousSibling(dest);
      if (prev) {
        BX.removeClass(prev, 'is-hover');
      }
      BX.removeClass(dest, 'is-hover');
    },

		compareItemDestDragFinish: function(curNode, x, y, e)
		{
      var dest = this,
          items = BX.findChildren(curNode.parentNode),
          indexCurrent = items.indexOf(curNode),
          indexDest = items.indexOf(dest),
          pos = BX.pos(dest),
          obTable = BX.findParent(dest, {tagName: 'table'});

      BX.removeClass(curNode, 'is-hover');
      BX.removeClass(dest, 'is-hover');
      var prev = BX.previousSibling(dest);
      if (prev) {
        BX.removeClass(prev, 'is-hover');
      }

      if (obTable) {
        var rows = BX.findChildren(obTable, {tagName: 'tr'}, true);
        if (rows.length) {
          for (var i in rows) {
            var currentCell = rows[i].children.item(indexCurrent),
                destCell = rows[i].children.item(indexDest);

            if (currentCell && destCell) {
              if (x - pos.left < pos.width / 2) {
                destCell.parentNode.insertBefore(currentCell, destCell);
              } else {
                destCell.parentNode.insertBefore(currentCell, destCell.nextSibling);
              }
            }
          }
        }

      }

      BX.onCustomEvent('OnCompareSort');
    },

		compareItemDragInit: function()
		{
      var target = BX.proxy_context.parentNode;

      if(undefined == target.onbxdragstart){
        target.onbxdragstart = this.compareItemDragStart;
        target.onbxdragstop = this.compareItemDragStop;
        target.onbxdrag = this.compareItemDrag;

        target.onbxdraghover = this.compareItemDragHover;
        target.onbxdraghout = this.compareItemDragOut;

        target.onbxdestdragfinish = this.compareItemDestDragFinish;

        jsDD.registerObject(target);
        jsDD.registerDest(target);
        BX.unbind(target, "mouseover",  BX.proxy(this.compareItemDragInit, this));
      }
    },
  };

  return CompareClass;
})();