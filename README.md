This extension provides a way for wiki admins to view the contents of 'phpinfo()' in 
environments like Container-based deploys where it might be difficult to simply create
a phpinfo script. The default permissions allow only Admin users (Wiki SysOps) to view the Special Page

A separate option 'Clock Info' provides details on available clock sources.  This comes in 
real handy when running your wiki on AWS (https://heap.io/blog/engineering/clocksource-aws-ec2-vdso),
especially when doing application profiling or deep monitoring like provided by e.g. New Relic. In these
situations you want to make sure you are using a [very precise clock](https://blog.trailofbits.com/2019/10/03/tsc-frequency-for-all-better-profiling-and-benchmarking/). See [here](https://access.redhat.com/solutions/18627) 
for instructions on changing your clock on RedHat systems and also a quick overview on 
hardware clock and system timer circuits. 

The options for Apache Server Status and Apache Info obviously depend on using Apache
but also require some setup of the Apache environment to allow access to server-status and server-info.


## Testing
This extension implements [the recommended entry points of Wikimedia CI for PHP and Front-end projects](https://www.mediawiki.org/wiki/Continuous_integration/Entry_points).

Before you can test and build code locally, you need:

* PHP 7.1, or later. (with Composer)
* Node.js 10, or later. (with npm)

You can meet all the software requirements without modifying your local system by using Docker and [Fresh](https://www.mediawiki.org/wiki/Selenium/Getting_Started/Run_tests_using_Fresh)

## PHP
To run the PHP code checks and unit tests:

* Run `composer update`

This will install testing software to `vendor/` in the current directory.

Now, run `composer test` whenever you want to run the automated checks and tests.

## Front-end
To run the checks for JavaScript, JSON, and CSS:

* Run `npm install`

This will intall testing software to `node_modules/` in the current directory.

Now, run `npm test` to run the automated front-end code checks..

## Contributing
git clone https://github.com/freephile/ServerInfo.git

## Credits
This extension is based on the work of James Montalvo and his [MezaExtension](https://github.com/enterprisemediawiki/MezaExt)

