EFQ Wrapper [![License](https://poser.pugx.org/atdrupal/efq-wrapper/license)](https://packagist.org/packages/atdrupal/efq-wrapper) [![Build Status](https://travis-ci.org/atdrupal/efq-wrapper.svg)](https://travis-ci.org/atdrupal/efq-wrapper)
====

A simple wrapper for Drupal's EntityFieldQuery class.

Base on `atdrupal\efq_wrapper\EntityHintEFQ`, we can generate classes those support autocomplete:

```php
<?php

/**
 * @method where__property__pid()
 * @method where__property__type()
 * @method where__property__label()
 * @method where__property__created()
 * @method where__property__changed()
 * @method where__property__url()
 * @method where__property__user()
 * @method sort__property__pid($direction = "ASC")
 * @method sort__property__type($direction = "ASC")
 * @method sort__property__label($direction = "ASC")
 * @method sort__property__created($direction = "ASC")
 * @method sort__property__changed($direction = "ASC")
 * @method sort__property__url($direction = "ASC")
 * @method sort__property__user($direction = "ASC")
 */
abstract class Profile2EntityHintEFQ extends \atdrupal\efq_wrapper\EntityHintEFQ {
    protected $type = 'profile2';
}

/**
 * @method Profile2OrganisationEntityHintEFQ where__field__field_organisation_name__value($value, $op = NULL)
 * @method Profile2OrganisationEntityHintEFQ where__field__field_organisation_name__format($value, $op = NULL)
 * @method Profile2OrganisationEntityHintEFQ sort__field__field_organisation_name__value($direction = "ASC")
 * @method Profile2OrganisationEntityHintEFQ sort__field__field_organisation_name__format($direction = "ASC")
 */
class Profile2OrganisationEntityHintEFQ extends Profile2EntityHintEFQ
{
    protected $bundle = 'organisation';

    public function __construct() {
        parent::__construct($this->type, $this->bundle);
    }
}
```
