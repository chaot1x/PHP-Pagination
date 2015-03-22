# PHP-Pagination
This pagination class automatically generate pagination markup based on bootstrap  http://getbootstrap.com/components/#pagination

##### Simple usage
```php
<?php
    // Include pagination class
    require_once 'PHP-Pagination.php';
    
    // Initialize class
    $pager = new Pagination();
    
    // Prepare pagination, var_dump($q) to see available variables
    $q = $pager->prepare($_GET['page'], 5);
    
    // Set total rows for example
    $total = 200;
    // Execute pagination
    echo $pager->total($total)->execute();
```
