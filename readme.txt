=== LH Membership Numbers ===
Contributors: shawfactor
Donate link: https://lhero.org/portfolio/lh-membership-numbers/
Tags: membership, users, members, cards, login, logon, username, ID, user ID, userID
Requires at least: 3.0
Tested up to: 4.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin to allow users to login by a number, their user ID (optionally prefixed)

== Description ==

**Users of membership sites often have and use a membership number. Yet WordPress uses alpha numeric string for usernames for logging in. Why not use both by using the <a href="http://lhero.org/plugins/lh-membership-numbers/">LH Membership Number plugin</a>.**

LH Membership Numbers allows a user to login by typing in their membership number. Their membership number is the unique user ID that every user is assigned in the database. Optionally administrators can assign a prefix to the membership number (eg for my sports league <a href="https://princesparktouch.com/">princesparktouch.com</a> it is PPT). That prefix can make the number far more unique (globally).


== Installation ==

1. Upload the `lh-membership-numbers` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Optionally navigate to Settings->Membership Numbers if you wish to add a prefix to the membership number (wordpress user ID) that the user will use to login. This is useful for name spacing and preventing collisions

== Frequently Asked Questions ==

= Does this modify the database? =
No it does not. It simply allows a user to logon using the users user ID (in place of the username). The ID is not the username, it is the WordPress users table primary key. Optionally this number can be prefixed by an alpha numeric of your choosing.

= What could you use this for? =
Membership organisations often have membership cards with membership numbers. This plugin could allow the member to log in with that number.

= How do I display the users Membership number in the frontend? =
Simply paste the shortcode [lh_membership_numbers_display_number] into a post, page, or CPT. Note this will display the logged in users membership number. If the visitor is not logged in an error message is displayed, as Wordpress cannot identify the user.

= How do I see the users Membership number in the backend? =
The membership number is displayed in a column in the backend.



== Changelog ==

= 1.00 =
* Initial release

= 1.01 =
* Added Icon

= 1.02 =
* Better readme

**1.03 June 22, 2016** 
* Added Settings link.

**1.04 July 27, 2017** 
* Added class check

**1.05 September 15, 2018** 
* Singleton pattern and shortcode