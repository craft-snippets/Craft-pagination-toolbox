## Pagination options

Here is the list of parameters that can be passed to `option` parameter of `getPagination()` function.

* `type` - type of pagination. Possible values: `ellipsis`, `logarithmic`. Default: `ellipsis`.
* `addSeoTags` - if SEO tags related to pagination should be inserted into the page template.
* `includeDefaultCss` - if default CSS styles should be used.
* `textPrev` - content of the "previous page" link. Default - "❮".
* `textNext` - content of the "next page" link. Default - "❯".
* `showNumberWidget` - if page number widget should be displayed.
* `showNumbers` - if numeric links should be displayed. Default: `true`.
* `showEllipsis` - if ellipsis elements should be displayed. Default: `true`.
* `showPrevNext` - if "previous page" and "next page" links should be displayed. Default: `true`.
* `showInactivePrevNext` - if inactive "previous page" and "next page" links should be displayed. Default: `false`.
* `cssClassCurrent` - CSS class of "current page" link element.
* `cssClassLink` - CSS class of link element.
* `cssClassEllipsis` - CSS class of ellipsis element.
* `cssClassNav` - CSS class of `<nav>` element wrapping pagination.
* `cssClassUl` - CSS class of `<ul>` element containing all links.
* `cssClassLi` - CSS class of `<li>` elements containing links. 

Parameters specific to page number widget that can be included in the pagination.

* `cssClassNumberWidget` - CSS class of page number widget.
* `cssClassNumberWidgetInput` - CSS class of page number widget - input element.
* `cssClassNumberWidgetInputOuter` - CSS class of page number widget - input element wrapper.
* `cssClassNumberWidgetLabel` - CSS class of page number widget - text label.
* `numberWidgetInstruction` - page number widget - aria label of input. Default: "Enter the page number you want to visit".
* `numberWidgetLabelOutOf` - page number widget - text label. Default: "out of".

Parameters specific only to ellipsis pagination:

* `textEllipsisBefore` - content of ellipsis displayed before current number. Default - "…".
* `textEllipsisAfter` - content of ellipsis displayed after current number. Default - "…".
* `neighbours` - number of numeric links displayed before and after current number. Default: 2.

Parameters specific only to logarithmic pagination:

* `groups` - how much link groups should be displayed. Default: 7.
* `textEllipsis` - content of ellipsis element. Default: "…".