<?php

namespace Altwaireb\Countries;

use Altwaireb\Countries\Traits\HasActivation;
use Altwaireb\Countries\Traits\HasConfiguration;
use Altwaireb\Countries\Traits\HasData;
use Altwaireb\Countries\Traits\HasException;
use Altwaireb\Countries\Traits\HasInsertable;
use Altwaireb\Countries\Traits\HasTables;
use Altwaireb\Countries\Traits\HasVerification;

class BaseCountriesService
{
    use HasActivation;
    use HasConfiguration;
    use HasData;
    use HasException;
    use HasInsertable;
    use HasTables;
    use HasVerification;
}
