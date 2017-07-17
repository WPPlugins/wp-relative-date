=== Relative Date ===
Contributors: dartiss
Donate link: https://artiss.blog/donate
Tags: relative, time, date, ago, days, hours, minutes, months, seconds, years
Requires at least: 4.6
Tested up to: 4.7.3
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display a relative date (e.g. "4 days ago")

== Description ==

Relative dates are where, instead of showing the exact date of a post, you can say it was posted "4 days ago". Tomorrow, this message would say "5 days ago". This plugin has been designed to be called from your theme (manually or automatically) to display these relative dates, as required.

All relative dates are shown up to 2 depths and are not designed to be 100% accurate (due to rounding), but rather to give the reader an idea as to how old something is.

Some examples...

Something that is 1 year and 2 months and 3 weeks old will be output as "1 year, 2 months".
Something that is 2 year and 0 months and 3 weeks old will be output as "2 years, 3 weeks".

Key features include...

* Change your theme yourself or automatically convert the dates
* Set output depth and your own dividers
* Specify two dates if you wish so you can add your own time range
* And much, much more!

Technical specification...

* Licensed under [GPLv2 (or later)](http://wordpress.org/about/gpl/ "GNU General Public License")
* Designed for both single and multi-site installations
* PHP7 compatible
* Fully internationalized, ready for translations **If you would like to add a translation to his plugin then please head to our [Translating WordPress](https://translate.wordpress.org/projects/wp-plugins/wp-relative-date "Translating WordPress") page**
* WCAG 2.0 Compliant at AA level

But, most importantly, there are no premium features and no adverts - this is 100% complete and free!

== Output Formats ==

The dates will be shown in one the following formats, depending on relevance...

* Years, months
* Years, weeks
* Months, weeks
* Months, days
* Weeks, days
* Days, hours
* Hours, minutes
* Minutes, seconds
* Seconds

== Adding the code to your theme ==

If you're happier changing your theme then this is definitely the best way to do it as you can tweak the output to just how you'd like it! To use, simply call either `get_relative_date` or `relative_date` - the first will return the relative date and the second will output it.

No parameters are required and if none are specified then it will simply create a relative date for the current post or page based on how old it is. However, you can specify a Unix format date (all dates specified must be in Unix format). If one is supplied then the difference will be between that and the current date.

In most cases you'll probably use this plugin to display a post time...

`if ( function_exists( 'get_relative_date' ) ) {
    echo 'Created ' . get_relative_date( get_the_time( 'U' ) ) . ' ago.';
}`

There is another parameter that you can use, if you wish to override the default divider (which is a comma). For example...

`get_relative_date( get_the_time( 'U' ) )`

Might produce a result of `3 days, 4 hours ago`. Whereas...

`get_relative_date( get_the_time( 'U' ), ' and ' )`

Would produce a result of `3 days and 4 hours ago`. Note that you need to specify any spaces as well around the divider that you need.

By default, all output has a maximum depth of 2 values - for example, `3 days, 4 hours ago`. If you specify a number of 1 or 2 as a parameter, however, you can control this depth. A depth of 1 with the previous example would output `3 days ago`.

With regard to the sequence of the parameters, it doesn't matter - you can specify the parameters in any order (up to 2 dates, 1 divider and 1 depth).

To actually add this to your theme you'll need to modify your theme files, for example `single.php`. Most themes will use `get_the_date`, `get_the_time`, `the_date` or `the_time` to output dates on a blog.

As an example, a theme may have the following...

`the_time( get_option( 'date_format' ) );`

This is getting the default WordPress data format and then outputting the current posts' created date in this format. To convert to a relative date, you'd replace this with...

`relative_date()`

== Using a second date ==

You can also provide a second date parameter, again in a Unix format date. If this is specified then the output will be the difference between the 2 dates that you’ve provided.

For example, the following would give the age of a child born on 1/1/2010, using a comma separator and displaying the output to 2 levels…

`get_relative_date( strtotime( '1/1/2010' ) , get_the_time( 'U' ) , ', ', '2' )`

== Show Relative Dates Automatically ==

If editing your theme isn't something you're comfortable with then you can achieve an automatic conversion.

Please bear in mind, however, that this will use default depth and divider settings and, depending on how your theme outputs it, the result may not make sense (depending on whether the original call hard-codes any before/after text or passes it as a parameter, as it should do).

Anyway, if you want to try it, head into your Administration screen and select Settings -> General. Near the bottom you should find a new option named "Use Relative Dates". Tick this box and then click on "Save Settings". Your blog dates should now show as relative dates.

== Installation ==

Relative Date can be found and installed via the Plugin menu within WordPress administration (Plugins -> Add New). Alternatively, it can be downloaded from WordPress.org and installed manually...

1. Upload the entire `wp-relative-date` folder to your `wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress administration.

Voila! It's ready to go.

== Screenshots ==

1. An example of the relative dates in use
2. The settings option that allows you to automate the conversion

== Changelog ==

[Learn more about my version numbering methodology](http://www.artiss.co.uk/2016/09/wordpress-plugin-versioning/ "WordPress Plugin Versioning")

= 2.0.1 =
* Maintenance: The plugin now requires a minimum of WordPress 4.6
* Maintenance: Improvements to README for the new format directory and to clarify certain points
* Bug: Fixed a bug that meant some dates were not outputting correctly (thanks to @ajkessel for reporting that)

= 2.0 =
* Enhancement: Added a new option to automatically convert existing blog output to a relative date
* Enhancement: After WP 4.6 the plugin text domain doesn't need to be loaded. So I don't!
* Enhancement: Updated output to be WCAG 2.0 compliant at AA level
* Maintenance: Squeezed all the files into just one, after realising it wouldn't be that big
* Maintenance: Apart from the core code that generates the relative date most of the existing code has been re-written
* Maintenance: Screenshots added and large swaths of the README re-written
* Maintenance: The time period text ("hour", "second", etc) - both singular and plural versions - was missing from the language files. Now added

= 1.2.2 =
* Maintenance: Added a text domain and domain path

= 1.2.1 =
* Maintenance: Updated support forum link

= 1.2 =
* Maintenance: Split code into separate files within an `includes` folder. Have a single root file that includes all the relevant code
* Maintenance: Re-written README
* Enhancement: Added new depth parameter
* Enhancement: Added internationalisation

= 1.1.1 =
* Maintenance: Removed dashboard widget

= 1.1 =
* Bug: Now calculates current time properly - gets server time in GMT and applies GMT difference, as specified in user's WP options
* Enhancement: New parameter added that allows user to change the divider used in output
* Enhancement: User does not now need to pass any dates to the function - in this case the relative date for the current post/page will be returned
* Enhancement: Renamed functions that user does not specify to avoid clashes with other plugins
* Enhancement: Allow parameters to be specified in any order
* Enhancement: Added useful links to plugin page

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.0.1 =
* Bug fix and a new minimum requirement of WordPress 4.6