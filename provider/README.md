# Provider 

This module implements the provider and provider wrappers.

#### Adding a new wrapper

Provider wrappers are stored in the `provider/wrapper` directory. To add a new wrapper, simply create a
class for your new wrapper and implement the interface `provider/wrapper/IProvider`. To code the implementation
clean, please add a new directory in `provider/wrapper` e.g. `provider/wrapper/mynewwrapper`. 
Please look at the current implemented wrappers to get an idea of the implementation strategy. 

#### Removing existing wrapper

To remove an existing wrapper, simply remove the directory containing the implementation e.g. `wrapper/infobip`
 and remove the `include_once "infobip/infobip.php";` import in `wrapper/provider-wrapper.php`. 
 This applies to all other wrappers.