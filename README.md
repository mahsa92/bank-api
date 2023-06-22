# Simple Bank API in Laravel

## Installation
1. <code> git pull git@github.com:mahsa92/bank-api.git </code>
2. <code> cd bank-api && ./vendor/bin/sail up </code>
3. Run migration:  <code> ./vendor/bin/sail artisan migrate</code>
3. Run seeders:  <code> ./vendor/bin/sail artisan db:seed</code>
4. Add these values to .env:
<code> 

    APP_PORT=80

    SMS_PROVIDER=kavenegar
</code>

## Usage Sample
<code> 
curl --location 'yourhost/api/transfer' \
--header 'Accept: application/json' \
--form 'sender_card_number="anyvalidCard"' \
--form 'receiver_card_number="anyvalidCard"' \
--form 'amount="1000"'
</code>
