RANCHER WRAPPER
=======================

This project is a rancher API wrapper developed for using as webhooks in a Continuous Integration tool like Jenkins.
<br/><br/>
It's based on the great PHP wrapper written for laravel [here](https://github.com/benmag/laravel-rancher).
<br/><br/>
It doesn't include lots of functions, but for our CI process is enough.
<br/><br/>
First of all, for use it you have to set these ENVS:

     - APP_ENV=local                                     // standard for laravel
     - APP_DEBUG=false                                   // standard for laravel
     - APP_KEY=SomeRandomString                          // standard for laravel
     - CACHE_DRIVER=file                                 // standard for laravel
     - SESSION_DRIVER=file                               // standard for laravel
     - QUEUE_DRIVER=sync                                 // standard for laravel

     - RANCHER_BASE_URL=http://rancher.domain:81/v1/     // Rancher Api url
     - RANCHER_ACCESS_KEY=access                         // Rancher Access Key
     - RANCHER_SECRET_KEY=secret                         // Rancher secret Key

     - BASIC_AUTH=1                                      // Enable Basic Auth
     - HTPASSWD=user:pass_encrypted                      // Basic Auth Data 


Rancher keys can be found in Rancher UI -> API -> Add Environment API KEY 
<br/><br/>
Basic Auth Data can be generated from command line or with online tools like [this](http://www.htaccesstools.com/htpasswd-generator/).
<br/><br/>
<br/>
This project is "Docker Ready" and can be deployed on same Rancher environment.
<br/><br/>
Available Endpoints:
--------------------
**/api/hosts**<br/>
Return the list of available hosts with hardware info
<br/><br/>
**/api/hosts/:id**<br/>
Return more host informations (like labels, endpoints etc)
<br/><br/>
**/api/stacks**<br/>
Return the list of all stacks configured
<br/><br/>
**/api/stacks/:id**<br/>
Return the stack and all services inside
<br/><br/>
**/api/stacks/:id/services**<br/>
Return an array of services for a stack
<br/><br/>
**/api/stacks/:id/services**<br/>
Return an array of services for a stack
<br/><br/>
**/api/stacks/:id/services/:id**<br/>
Return a service from a stack
<br/><br/>
**/api/services**<br/>
Return the list of all services configured
<br/><br/>
**/api/services/:id**<br/>
Return a single service
<br/><br/>
**/api/services/:id/upgrade**<br/>
Upgrade a service pulling ALWAYS the new image and starting new containers before stopping old ones.
<br/><br/>
**/api/services/:id/finish_upgrade**<br/>
Finish the upgrade of a service, can be called immediately after the upgrade request, it waits that the service state changes to 'upgraded' and it finishes the upgrade changing the state to active again. 
<br/><br/>
**/api/services/:id/scale/:count**<br/>
Can scale a service with the count of instance passed in url.
<br/><br/>


Use with Jenkins:
--------------------
For use it with jenkins you can install the [HTTP Request Plugin](https://wiki.jenkins-ci.org/display/JENKINS/HTTP+Request+Plugin) and call first the "upgrade" endpoint and after the "finish_upgrade" endpoint.<br/>
The jenkins build will fail if something goes wrong during the upgrade process and the build will be completed only when the service will finish the upgrade.