Pagination Silex
=========

Adds pagination functionality to a Silex app. Used in tandem with Paginator.

## Requirements

- PHP >= 5.3.3
- [Kosinix\Paginator](https://github.com/kosinix/paginator) (Install it independently)
- \Symfony\Component\Routing\Generator\UrlGenerator (Included with Silex)

## Installation

### Manual

* Download the zip file from the Github repository.
* Unpack the zip file and include the files in your project.
* Include the class in /src/:

```php
require_once '/path/to/src/Kosinix/Pagination.php'; // Change this to the correct path
```

### Composer

Inside your project directory, open the command line and type:

```php
composer require kosinix/pagination:dev-master --prefer-dist
```

Include the autoload.php found in vendor/:

```php
require_once '/path/to/vendor/autoload.php'; // Change this to the correct path
```

## Usage
Example, inside a Controller Provider:

```php
$controllers->get('/products/{page}/{sort_by}/{sorting}', function (Request $request, Application $app, $page, $sort_by, $sorting) {
    $sql = 'SELECT COUNT(*) AS `total` FROM product';
    $count = $app['db']->fetchAssoc($sql);
    $count = (int) $count['total'];

    /** @var \Kosinix\Paginator $paginator */
    $paginator =  $app['paginator']($count, $page);

    $sql = sprintf('SELECT
        *
    FROM
        product
    WHERE
        1=1
    ORDER BY %s %s
    LIMIT %d,%d',
        $sort_by, strtoupper($sorting), $paginator->getStartIndex(), $paginator->getPerPage());

    $products = $app['db']->fetchAll($sql);

    $pagination = new Pagination($paginator, $app['url_generator'], 'admin/products', $sort_by, $sorting);

    return $app['twig']->render('admin/products/index.twig', array(
        'products' => $products,
        'pagination' => $pagination
    ));
})->value('page', 1)
->value('sort_by', 'created')
->value('sorting', 'asc')
->assert('page', '\d+') // Numbers only
->assert('sort_by','[a-zA-Z_]+') // Match a-z, A-Z, and "_"
->assert('sorting','(\basc\b)|(\bdesc\b)') // Match "asc" or "desc"
->bind('admin/products');
```

Add pagination.twig in your views:

```html
{% if pagination.isNeeded() %}
<ul class="pagination">
	<li><a href="{{ pagination.firstPageUrl() }}">&laquo;&laquo; First</a></li>
	{% if pagination.isPreviousPage() %}
		<li><a href="{{ pagination.previousPageUrl() }}">&laquo; Prev</a></li>
	{% endif %}
	{% for page in range(pagination.getPaginator().shortPageStart(), pagination.getPaginator().shortPageEnd()) %}
		{% if pagination.getPaginator().getCurrentPage() == page %}
			<li  class="active"><a href="{{ pagination.pageUrl(page) }}">{{page}}</a></li>
		{% else %}
			<li><a href="{{ pagination.pageUrl(page) }}">{{page}}</a></li>
		{% endif %}
	{% endfor %}
	{% if pagination.isNextPage() %}
		<li><a href="{{ pagination.nextPageUrl() }}">Next &raquo;</a></li>
	{% endif %}
	<li><a href="{{ pagination.lastPageUrl() }}">Last &raquo;&raquo;</a></li>
</ul>
{% endif %}
```

And use it in your view file:

```html
<h1>Products</h1>
{% if products %}
    <table class="table table-bordered">
        <tr>
            <th><a href="{{ pagination.sortingUrl('id') }}">ID</a></th>
            <th><a href="{{ pagination.sortingUrl('name') }}">Name</a></th>
            <th><a href="{{ pagination.sortingUrl('quantity') }}">Quantity</a></th>
            <th><a href="{{ pagination.sortingUrl('description') }}">Description</a></th>
            <th><a href="{{ pagination.sortingUrl('created') }}">Date</a></th>
        </tr>
        {% for product in products %}
            <tr>
                <td>{{ product.id }}</td>
                <td>{{ product.name }}</td>
                <td>{{ product.quantity }}</td>
                <td>{{ product.description }}</td>
                <td>{{ product.created|date("F d, Y")  }}</td>
            </tr>
        {% endfor %}
    </table>
    <div class="text-center">
        {% include 'pagination.twig' %}
    </div>
{% else %}
    <p>No products found.</p>
{% endif %}
```

## License

- MIT

