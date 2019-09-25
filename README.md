# Bulk-sms SC7 test

### Usage and design

> Sending bulksms (bulksms)

Bulksms is the entry point to the bulksms service. Example of calling the service 
```php
$bulkSms = new BulkSms();

$from = '+2541234567';
$recipients = ["+2541234567"];

$helloStr = str_repeat("Hello testing", 100);
$bulkSms->sendMessages([new Message($helloStr, $from, $recipients)]);
```

> Realtime updating provider

It's possible to update the configuration provider realtime. Anywhere in the implementation, the 
developer can update the config. Also from the api, it should be possible to update the default 
provider by calling the updateConfigProvider.

``Config::updateConfigProvider("nexmo");`` will set nexmo as the default provider. Currently, there
are 3 supported providers `nexmo`, `infobip`, and `africatalking`.

> Message lifecycle & status

After receiving the message, it's saved in the db and queued in a rabbitmq queue. The dispatcher will
listen to the queue and with the set default provider, send the message and update the status of 
the message in the database. Currently, there are 3 supported status `Sending`, `Sent` and `Failed.` 

> Multiple recipients and campaigns

To send a message to multiple recipients, simply add the recipient contacts in the recipient array.
Example:

```php
$from = '+2541234567';
$recipients = ['+2561234567', '+2541234567', '+2551234567'];

$bulkSms->sendMessage(new Message("Campaign", $from, $recipients));
```

> Handling long messages 

When a message is longer than 918, it is split into sizeable chunks and sent as separate messages.

> Implementing provider wrappers

Provider wrappers are stored in the `provider/wrapper` directory. To add a new wrapper, simply create a
class for your new wrapper and implement the interface `provider/wrapper/IProvider`. To code the implementation
clean, please add a new directory in `provider/wrapper` e.g. `provider/wrapper/mynewwrapper`. 
Please look at the current implemented wrappers to get an idea of the implementation strategy. 

