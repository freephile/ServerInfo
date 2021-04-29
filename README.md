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

This extension includes the standard code checkers for PHP and JavaScript code in Wikimedia projects
(see https://www.mediawiki.org/wiki/Continuous_integration/Entry_points).
To take advantage of this automation.

## Credits
This extension is based on the work of James Montalvo and his [MezaExtension](https://github.com/enterprisemediawiki/MezaExt)

