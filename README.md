# GamePlanner

## Installation

_composer.json_
```json
{
  "name": "yourprojectname"
  , "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/silentx1981/gameplanner"
    }
  ]
  , "require": {
     "php": "^7.0"
     ,"silentx81/gameplanner": "^0.1.0"
  }
}
```

## Usage
_index.php_

```php
<?php

namespace GamePlanner;

require __DIR__."/../vendor/autoload.php";

$planner = new Planner();
$plan = $planner->generate(4);
```


