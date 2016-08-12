RANCHER WRAPPER
=======================

This project is a rancher API wrapper developed for using as webhooks in a Continuous Integration tool like Jenkins.  
  
It's based on the great PHP wrapper written for laravel [here](https://github.com/benmag/laravel-rancher).  
  
It doesn't include lots of functions, but for our CI process is enough.  
  
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
  
Basic Auth Data can be generated from command line or with online tools like [this](http://www.htaccesstools.com/htpasswd-generator/).  
  
  
This project is "Docker Ready" and can be deployed on same Rancher environment.  
    
Available Endpoints:
--------------------
**/api/hosts**  
Return the list of available hosts with hardware info  
  
**/api/hosts/:id**  
Return more host informations (like labels, endpoints etc)  
  
**/api/stacks**  
Return the list of all stacks configured  
  
**/api/stacks/:id**  
Return the stack and all services inside  
  
**/api/stacks/:id/services**  
Return an array of services for a stack  
  
**/api/stacks/:id/services/:id**  
Return a service from a stack  
  
**/api/services**  
Return the list of all services configured  
  
**/api/services/:id**  
Return a single service  
  
**/api/services/:id/upgrade**  
Upgrade a service pulling ALWAYS the new image and starting new containers before stopping old ones.  
  
**/api/services/:id/finish_upgrade**  
Finish the upgrade of a service, can be called immediately after the upgrade request, it waits that the service state changes to 'upgraded' and it finishes the upgrade changing the state to active again.  
  
**/api/services/:id/scale/:count**  
Can scale a service with the count of instance passed in url.  
  
  
  
Use with Jenkins:
--------------------
For use it with jenkins you can install the [HTTP Request Plugin](https://wiki.jenkins-ci.org/display/JENKINS/HTTP+Request+Plugin) and call first the "upgrade" endpoint and after the "finish_upgrade" endpoint.  
The jenkins build will fail if something goes wrong during the upgrade process and the build will be completed only when the service will finish the upgrade.  
