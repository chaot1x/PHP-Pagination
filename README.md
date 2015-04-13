# PHP-Pagination
This pagination class automatically generate pagination markup based on bootstrap  http://getbootstrap.com/components/#pagination

##### Simple usage
```php
<?php
    /* Include Pagination class */
    require_once 'Pagination.php';
    
    /* Fix warning if $_GET['page'] isn't set */
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    
    /* Initialize class */
    $pager = new Pagination($page, 5);
    
    /* Optional method to make custom templates */
    $pager->makeTemplate('new-template', [
        'base' => '<ul>%s</ul>',
        'active' => '<li class="current">%d</li>',
        'disabled' => '<li class="disabled">...</li>',
        'link' => '<li><a href="%s">%d</a></li>',
        'prev' => '<li class="previous"><a href="%s">Previous</a></li>',
        'next' => '<li class="next"><a href="%s">Next</a></li>',
    ]);
    
    /* Set custom template, also can be used in chaining ($pager->template('new-template')->execute();) */
    $pager->template('new-template');
    
    /* Set total rows for example */
    $total = 200;
    /* Execute pagination */
    echo $pager->total($total)->execute();
```
