# [WP Generate Passphrase]

[![Maintainability](https://api.codeclimate.com/v1/badges/540ed1258f107c5d9f2b/maintainability)](https://codeclimate.com/github/dmchale/wp-generate-passphrase/maintainability)

** This is the public respository for the latest DEVELOPMENT copy of the plugin. There is absolutely no guarantee, 
express or implied, that the code you find here is a stable build. For official releases, please see the 
WordPress repository at https://wordpress.org/plugins/wp-generate-passphrase/ (coming soon) **
  
Change the default behavior of WordPress to generate passphrases, not passwords.
## Installation
1. Install to WordPress plugins as normal and activate.
## Usage
1. Basic usage of the plugin requires no configuration.
2. Optionally, you may use the Settings page to adjust the default behavior of how the passphrases are generated. (once supported)
## History
1. Initial version of the plugin simply overrides the results of `wp_generate_password()` with a 4-word passphrase built using a static dictionary of available words.
2. Version 1.1 added the ability for site admins to define their own word list, which would supplement the default list.
## Credits
Authored by Dave McHale
## License
As with all WordPress projects, this plugin is released under the GPL 
