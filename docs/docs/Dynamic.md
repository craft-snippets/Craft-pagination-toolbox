# Dynamic pagination

Dynamic pagination allows users to browse paginated content lists **without refreshing the page**. It uses AJAX requests to replace the content when you click the pagination link. 

Dynamic pagination has all the functionalities of standard pagination with benefit of making browsing paginated content lists faster and more fluid. It properly changes the URL address when subpage changes. It can be used with faceted filters or search forms - any query params stay in URL address when you change the subpage. If for some reason JS is disabled - dynamic pagination will not becemo unusable - it will just work like the regular pagination. 

## Using dynamic pagination

Place this code in a the separate template file - this template will be reloaded using AJAX. The whole content of this template file needs to be wrapped in HTML element (like `div`) which has attribute outputted by `getContainerAttribute()` function. Thanks to that, JS will know what to replace when the subpage of pagination changes. In this example, this file should be named `template_file_path.twig`.

```twig
{# perform pagination #}
{% paginate craft.entries.limit(10) as pageInfo, pageEntries %}

{# enable dynamic pagination #}
{{craft.pagination.enableDynamicPagination(pageInfo, 'template_file_path', {
  'scrollOnRequest': true,
}) }}

{# wrapper for content that will be replaced #}
<div {{craft.pagination.getContainerAttribute()}}>

{# output pagination widget #}
{{craft.pagination.getPagination(pageInfo, {
	type: 'ellipsis',
}).render() }}	

{# some example list of paginated content #}
<ul>
{% for entry in pageEntries %}
   <li>
        <a href="{{entry.url}}">{{entry.id}}</a>
   </li>
{% endfor %}
</ul>

</div>
```

As shown in the code example, before outputting pagination HTML using `getPagination()` function, you need to activate dynamic pagination using `enableDynamicPagination()` function. Its parameters are:

* `pageInfo` - pageInfo object provided by Craft `paginate` tag.
* `templatePath` - path to a template file that contains dynamic pagination.
* `options` - optional parameters

Options that can be included in object passed to `enableDynamicPagination()` function are:

* `scrollOnRequest` - if page should scroll to the top of the dynamic pagination container when the subpage changes. Default: `true`.
* `usePreloader` - if CSS class is applied to dynamic pagination container during subpage load. Default: `true`.
* `cssClassLoading` - CSS class that is applied to dynamic pagination container during subpage load - can be used to display the loading effect or some kind of preloader. Default: `pagination-loading`. Default class has simple "fade in-out" effect assigned in the default CSS styles.

## Javascript events with Dynamic pagination

The plugin provides Javascript events allowing execution of code when subpage of paginated list changes. Here is how they can be used:

```js
document.querySelector('[data-dynamic-pagination-wrapper]').addEventListener('dynamic-pagination-before', function (e) {
	console.log('before page change')
}, false);
document.querySelector('[data-dynamic-pagination-wrapper]').addEventListener('dynamic-pagination-after', function (e) {
	console.log('after page change')
}, false);

document.querySelector('[data-dynamic-pagination-wrapper]').addEventListener('dynamic-pagination-error', function (e) {
	console.log('ajax error')
}, false);
```