This extension provides a way for wiki admins to view the contents of 'phpinfo()' in 
environments like Container-based deploys where it might be difficult to simply create
a phpinfo script. The default permissions allow only Admin users to view the Special Page

This automates the recommended code checkers for PHP and JavaScript code in Wikimedia projects
(see https://www.mediawiki.org/wiki/Continuous_integration/Entry_points).
To take advantage of this automation.

1. install nodejs, npm, and PHP composer
2. change to the extension's directory
3. `npm install`
4. `composer install`

Once set up, running `npm test` and `composer test` will run automated code checks.
