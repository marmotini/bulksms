# Bulk-sms SC7 test

### Setup 

To setup the project, follow the below instructions. 

1. Setup the queue. Follow the instructions in the `queue/README.md` file. 
2. Ensure `config.json` has the correct credentials for the providers, queues e.t.c
3. Update the dependencies by running `./composer install`
4. Update the test file with a number of your test preference. Run the test file `php bulk-test.php`
5. On a different console, run the dispatcher `php dispatcher.php`
6. lookout for a text message from the test phone provided

### Usage and design

#### Sending bulk messages (bulksms)

Below is an example of sending an sms message to a single recipient. Multiple messages can be included
 in the array parameter.

```php
$bulkSms = new BulkSms();

$from = '+254123456789';
$recipients = ["+2541234567"];

$helloStr = str_repeat("Hello testing", 100);
$bulkSms->sendMessages([new Message($helloStr, $from, $recipients)]);
```

#### Realtime updating provider

It's possible to update the default configuration provider, realtime. Anywhere in the implementation, the 
developer can update the config by calling `Config::updateConfigProvider` method. 
From the api, it should be possible to update the default provider by calling the updateConfigProvider too. This
will facilitate update on the default provider using a rest api call.

``Config::updateConfigProvider("nexmo");`` will set nexmo as the default provider. Currently, there
are 3 supported providers `nexmo`, `infobip` and `africatalking`.

#### Message lifecycle & status

After receiving the message, it's saved in the db and queued in a rabbitmq queue. The dispatcher will
listen to the queue and with the set default provider, send the message to the set recipients and update the 
status of the message in the database. Currently, there are 3 supported message status `Sending`, `Sent` and `Failed.` 

#### Multiple recipients and campaigns

To send a message to multiple recipients, simply add the recipient contacts in the recipient array.
Example:

```php
$from = '+254123456789';
$recipients = ['+2561234567', '+2541234567', '+2551234567'];

$bulkSms->sendMessage(new Message("Campaign", $from, $recipients));
```

#### Handling long messages 

When a message is longer than 918, it is split into sizeable chunks and sent as separate messages.

#### Implementing provider wrappers

Provider wrappers are stored in the `provider/wrapper` directory. To add a new wrapper, simply create a
class for your new wrapper and implement the interface `provider/wrapper/IProvider`. To code the implementation
clean, please add a new directory in `provider/wrapper` e.g. `provider/wrapper/mynewwrapper`. 
Please look at the current implemented wrappers to get an idea of the implementation strategy. 

