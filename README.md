opencaching_international
=========================

experimental opencaching based on symphony framework and ORM doctrine.
Geocaches database to be gathered from local opencaching nodes using okapi

Instructions:

1. Checkout/Clone
2. run composer update
3. rename /app/config/parameters.yml.dist to parameters.yml, edit witch your db credentials
4. create db using doctrine: php app/console doctrine:database:create
5. update database schema using doctrine: php app/console doctrine:schema:update --force


Symphony documentation: http://symfony.com/doc/current/index.html
doctrine ORM guide: http://symfony.com/doc/master/book/doctrine.html#book-doctrine-field-types

Feel free to join!
